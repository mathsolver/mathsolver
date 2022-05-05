<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Fraction;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AddFractions extends Step
{
    /**
     * Add fractions with different denominators together.
     */
    public function handle(Node $node): Node
    {
        // Instantiate a new fraction
        $fraction = new Fraction(0, 1);

        // Find all fractions and convert them to an array
        // of [numerator, denominator]
        $fractions = $node->children()
            ->filter(fn (Node $child) => $child->isNumeric())
            ->each(fn (Node $fraction) => $node->removeChild($fraction))
            ->map(
                fn (Node $fraction) => $fraction->value() === 'frac'
                ? [$fraction->child(0)->value(), $fraction->child(1)->value()]
                : [$fraction->value(), 1]
            );

        // Don't do anything if there are no fractions
        if ($fractions->count() === 0) {
            return $node;
        }

        // If there is only one fraction, append the old
        // fraction so that the simplification process
        // won't be done in this step (but in the
        // `SimplifyFractions` step instead)
        if ($fractions->count() === 1) {
            $fraction = new Fraction($fractions->first()[0], $fractions->first()[1]);
            return tap($node)->appendChild($fraction->node());
        }

        // Add each fraction up
        foreach ($fractions as $fractionArray) {
            $fraction = $fraction->add($fractionArray[0], $fractionArray[1]);
        }

        // Append the new fraction
        $node->appendChild($fraction->simplify()->node());
        return $node;
    }

    /**
     * Only run in additions.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '+';
    }
}
