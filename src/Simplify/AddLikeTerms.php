<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AddLikeTerms extends Step
{
    public bool $shouldRun = false;

    public function handle(Node $node): Node
    {
        /*
        For each term:
            Find the non-numeric factors
            Serialize those factors
            Look them up in an array:
                If the non-numeric does not exist yet:
                    Add it with the numeric factors as the value
                Else:
                    Append the numeric factors to the list of values

        For each new term:
            Create a new product
            Append the unserialized non-numeric factors to that product
            Add the numeric factors up and append the result to the product

        Return the final plus-node
        */

        $clone = $node->clone();
        $totals = new Collection();

        $node->children()
            ->filter(fn (Node $child) => !is_numeric($child->value()) && $child->numericChildren()->count() < 2)
            ->filter(fn (Node $child) => $child->value() !== 'frac' && $child->children()->filter(fn (Node $child) => $child->value() === 'frac')->count() === 0)
            ->each(fn (Node $child) => $node->removeChild($child))
            ->map(fn (Node $child) => $child->value() === '*' ? $child : tap(new Node('*'))->appendChild($child))
            ->each(function (Node $product) use ($totals) {
                $coefficient = $product->numericChildren()->first()?->value() ?? 1;
                $factors = $this->serializeFactors($product->nonNumericChildren());

                if ($totals->has($factors)) {
                    $this->shouldRun = true;
                    $totals->put($factors, $totals->get($factors) + $coefficient);
                } else {
                    $totals->put($factors, $coefficient);
                }
            });

        if (!$this->shouldRun) {
            return $clone;
        }

        $totals->each(function (float $coefficient, string $factors) use ($node) {
            $factors = unserialize($factors);

            if ($coefficient == 1 && $factors->count() == 1) {
                $node->appendChild($factors->first());
                return;
            }

            if ($coefficient == 0) {
                return;
            }

            $product = $node->appendChild(new Node('*'));
            $product->setChildren($factors);

            if ($coefficient != 1) {
                $product->appendChild(new Node($coefficient), true);
            }
        });

        if ($node->children()->count() === 0) {
            return new Node(0);
        }

        return $node;
    }

    public function shouldRun(Node $node): bool
    {
        return $node->value() === '+';
    }

    protected function serializeFactors(Collection $factors): string
    {
        // Set the parent to null, as that shouldn't have influence on the serialized value
        $factors = $factors->map(fn (Node $factor) => $factor->clone()->setParent(null))->sortBy(fn (Node $node) => $node->value())->values();

        return serialize($factors);
    }
}
