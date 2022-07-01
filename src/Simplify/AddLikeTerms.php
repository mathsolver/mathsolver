<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AddLikeTerms extends Step
{
    public static ?string $docs = <<<'MARKDOWN'
        Sometimes terms can be combined together:

        $$ 2x + 3x = 5x $$

        This is only possible when the letters are the same:

        $$ 2x - 3y + 7x + 5y = 9x + 2y $$

        When letters do not have a leading coefficient, then you can use \\( 1 \\):

        $$ x + 2x = 3x $$
        MARKDOWN;

    /**
     * Determine whether to run the second part of this function, based on the first part.
     *
     * This is to not change the order of terms when not actually changing anything.
     */
    public bool $shouldRun = false;

    /**
     * Add like terms: 7x + 5x => 12x, y^3 + y^3 => 2y^3.
     */
    public function handle(Node $node): Node
    {
        $clone = $node->clone();

        $totals = $this->calculateTotals($node);

        if (!$this->shouldRun) {
            return $clone;
        }

        // Append the returned value unless it equals 0
        $totals->each(function (float $coefficient, string $factors) use ($node) {
            if ($child = $this->buildTerm($coefficient, $factors)) {
                $node->appendChild($child);
            }
        });

        if ($node->children()->count() === 0) {
            return new Node(0);
        }

        return $node;
    }

    /**
     * Run in additions.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '+';
    }

    /**
     * Loop over each term, and do the following:
     * - If the term is already in the $totals collection, add the new coefficient up
     * - If not, then add the coefficient to the $totals collection.
     */
    protected function calculateTotals(Node $node): Collection
    {
        $totals = new Collection();

        foreach ($node->children() as $child) {
            // Skip anything that is a number or contains more than one number
            if (is_numeric($child->value()) || $child->numericChildren()->count() > 1) {
                continue;
            }

            // Skip anything that is a fraction or that contains a fraction
            if ($child->value() === 'frac' || $child->children()->filter(fn (Node $child) => $child->value() === 'frac')->count() > 0) {
                continue;
            }

            // Remove the term from its parent
            $node->removeChild($child);

            // Wrap the term in a product
            if ($child->value() !== '*') {
                $child = tap(new Node('*'))->appendChild($child);
            }

            // Find the coefficient and the factors
            $coefficient = $child->numericChildren()->first()?->value() ?? 1;
            $factors = $this->serializeFactors($child->nonNumericChildren());

            // Add the coefficient and the factors to the $totals collection
            if ($totals->has($factors)) {
                $this->shouldRun = true;
                $oldCoefficient = (float) $totals->get($factors);
                $totals->put($factors, $oldCoefficient + $coefficient);
            } else {
                $totals->put($factors, $coefficient);
            }
        }

        return $totals;
    }

    /**
     * Serialize a collection of factors.
     *
     * Set the parent to null, as that shouldn't have influence on the serialized value.
     */
    protected function serializeFactors(Collection $factors): string
    {
        $factors = $factors
            ->map(fn (Node $factor) => $factor->clone()->setParent(null))
            ->sortBy(fn (Node $node) => $node->value())
            ->values();

        return serialize($factors);
    }

    /**
     * Multiply the non-numeric factors by the computed coefficient.
     */
    protected function buildTerm(float $coefficient, string $serializedFactors): Node|null
    {
        $factors = unserialize($serializedFactors);

        // Don't return a product, just the first factor
        if ($coefficient == 1 && $factors->count() == 1) {
            return $factors->first();
        }

        // Multiply by zero, so the factors don't matter
        if ($coefficient == 0) {
            return null;
        }

        // Build a product and append the factors
        $product = new Node('*');
        $product->setChildren($factors);

        // Only append the coefficient if is it not 1
        if ($coefficient != 1) {
            $product->appendChild(new Node($coefficient), true);
        }

        return $product;
    }
}
