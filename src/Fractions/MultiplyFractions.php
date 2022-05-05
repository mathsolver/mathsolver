<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Fraction;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MultiplyFractions extends Step
{
    /**
     * Add fractions with different denominators together.
     */
    public function handle(Node $node): Node
    {
        // Instantiate a new fraction
        $fraction = new Fraction(1, 1);

        // Find all fractions and convert them to an array
        // of [numerator, denominator]
        $fractions = $node->children()->filter(fn (Node $child) => $child->isNumeric());

        // Don't do anything if there are no fractions to be multiplied
        if ($fractions->count() < 2) {
            return $node;
        }

        $fractions = $fractions
            ->each(fn (Node $fraction) => $node->removeChild($fraction))
            ->map(
                fn (Node $fraction) => $fraction->value() === 'frac'
                ? [$fraction->child(0)->value(), $fraction->child(1)->value()]
                : [$fraction->value(), 1]
            );

        // Multiply each fraction
        foreach ($fractions as $fractionArray) {
            $fraction = $fraction->multiply($fractionArray[0], $fractionArray[1]);
        }

        // Append the new fraction
        $node->appendChild($fraction->node());
        return $node;
    }

    /**
     * Only run in multiplications.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '*';
    }
}
