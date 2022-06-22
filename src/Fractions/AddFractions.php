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
        $fractions = $node->children()->filter(function (Node $child) {
            if (!$child->isNumeric()) {
                return false;
            }

            if ($child->value() === 'frac') {
                return (float) $child->child(0)->value() === floor($child->child(0)->value())
                    && (float) $child->child(1)->value() === floor($child->child(1)->value());
            }

            return (float) $child->value() === floor($child->value());
        });

        // Don't do anything if there are no fractions
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

        // Add each fraction up
        foreach ($fractions as $fractionArray) {
            $fraction = $fraction->add($fractionArray[0], $fractionArray[1]);
        }

        // Append the new fraction
        $node->appendChild($fraction->node());
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
