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
        return new Node(1);
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '^' && $node->children()->last()->value() == 0;
    }
}
