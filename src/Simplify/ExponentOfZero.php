<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class ExponentOfZero extends Step
{
    /**
     * Replace all powers with an exponent of zero by a one.
     *
     * For example: 6^0 -> 1, x^0 -> 1.
     */
    public function handle(Node $parentNode): Node
    {
        if ($parentNode->value() !== '^') {
            return $parentNode;
        }

        if ($parentNode->children()->last()->value() == 0) {
            return new Node(1);
        }

        return $parentNode;
    }
}
