<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;

trait FindValues
{
    /**
     * Return the numerator if it is a fraction and else just the node.
     */
    protected function findNumerator(Node $fraction): Node
    {
        return $fraction->value() === 'frac'
            ? $fraction->child(0)
            : $fraction;
    }

    /**
     * Return the denominator if it is a fraction and else just 1.
     */
    protected function findDenominator(Node $fraction): Node
    {
        return $fraction->value() === 'frac'
            ? $fraction->child(1)
            : new Node(1);
    }
}
