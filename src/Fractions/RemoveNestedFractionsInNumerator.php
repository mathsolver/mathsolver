<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class RemoveNestedFractionsInNumerator extends Step
{
    /**
     * Remove nested fractions in the numerator of a fraction.
     *
     * For example: (2/3)/5 => 2/(3*5)
     */
    public function handle(Node $fraction): Node
    {
        // Find/create the times node
        if ($fraction->child(1)->value() === '*') {
            $times = $fraction->child(1);
        } else {
            $times = $fraction->appendChild(new Node('*'));

            // Append the numerator of the root fraction to $times
            $times->appendChild($fraction->child(1));
            $fraction->removeChild($fraction->child(1));
        }

        // Append the denominator of the nested fraction
        $times->appendChild($fraction->child(0)->child(1));
        $fraction->replaceChild($fraction->child(0), $fraction->child(0)->child(0));

        return $fraction;
    }

    /**
     * Check if there is a fraction in the numerator of a fraction.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && $node->child(0)->value() === 'frac';
    }
}
