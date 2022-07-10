<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class RemoveNestedFractionsInDenominator extends Step
{
    /**
     * Remove nested fractions in the denominator of a fraction.
     *
     * For example: 2/(3/5) => (2*5)/3
     */
    public function handle(Node $fraction): Node
    {
        // Find/create the times node
        if ($fraction->child(0)->value() === '*') {
            $times = $fraction->child(0);
        } else {
            $times = new Node('*');

            // Append the numerator of $fraction to the $times node
            $times->appendChild($fraction->child(0)->clone()->wrapInBrackets('*'));
            $fraction->replaceChild($fraction->child(0), $times);
            $times->child(-1)->setParent($times);
        }

        // Append the denominator of the nested fraction
        $times->appendChild($fraction->child(1)->child(1));
        $fraction->replaceChild($fraction->child(1), $fraction->child(1)->child(0));

        return $fraction;
    }

    /**
     * Check if there is a fraction in the denominator of a fraction.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && $node->child(1)->value() === 'frac';
    }
}
