<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;

class MultiplyLikeFactors extends Step
{
    public bool $foundDouble = false;

    /**
     * Replace all double letters with a power.
     *
     * For example "x * x * x" -> "x^3".
     */
    public function handle(Node $node): Node
    {
        $totals = $this->calculateTotals($node);

        if (!$this->foundDouble) {
            return $node;
        }

        $node->removeAllChildren();

        $node = $this->appendCalculatedTotals($node, $totals);

        return $this->getReturnValue($node);
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '*';
    }

    /**
     * Calculate the totals of each term and return that collection.
     *
     * @return Collection<string,integer>
     */
    protected function calculateTotals(Node $parentNode): Collection
    {
        $totals = new Collection();

        $parentNode->children()->each(function (Node $node) use ($totals) {
            if ($node->value() === '^') {
                $string = $node->children()->first()->toString();
                $increment = $node->children()->last()->value();
            } else {
                $string = $node->toString();
                $increment = 1;
            }

            if ($totals->has($string)) {
                $this->foundDouble = true;
                $totals->put($string, $totals->get($string) + $increment);
            } else {
                $totals->put($string, $increment);
            }
        });

        return $totals->sortKeys(SORT_NATURAL);
    }

    /**
     * Loop over the calculated totals and append them to the parent node.
     *
     * @param Collection<string,integer> $totals
     */
    protected function appendCalculatedTotals(Node $parentNode, Collection $totals): Node
    {
        $totals->each(function (int $count, string $node) use ($parentNode) {
            if ($count === 1) {
                $parentNode->appendChild(Node::fromString($node));
                return;
            }

            $power = new Node('^');

            if (is_numeric($node) && $node < 0 && $count % 2 === 0) {
                // add brackets when the base is a negative number
                tap($power->appendChild(new Node('(')))->appendChild(Node::fromString($node));
            } else {
                $power->appendChild(Node::fromString($node));
            }

            $power->appendChild(new Node($count));
            $parentNode->appendChild($power);
        });

        return $parentNode;
    }

    /**
     * When the multiplication has only one child left (the power), return just that one child.
     */
    protected function getReturnValue(Node $node): Node
    {
        if ($node->children()->count() === 1) {
            return tap($node->children()->first())->setParent(null);
        }

        return $node;
    }
}
