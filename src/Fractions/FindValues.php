<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;

trait FindValues
{
    /**
     * Return the numerator if it is a fraction and else just the node.
     */
    protected function findNumerator(Node $node): Node
    {
        return $node->value() === 'frac'
            ? $node->child(0)
            : $node;
    }

    /**
     * Return the denominator if it is a fraction and else just 1.
     */
    protected function findDenominator(Node $node): Node
    {
        return $node->value() === 'frac'
            ? $node->child(1)
            : new Node(1);
    }
}
