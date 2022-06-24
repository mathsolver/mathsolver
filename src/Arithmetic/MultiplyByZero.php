<?php

namespace MathSolver\Arithmetic;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MultiplyByZero extends Step
{
    /**
     * Replace all multiplications with a zero with a zero.
     *
     * For example: 7 * 0 -> 0, 0 * y -> 0.
     */
    public function handle(Node $node): Node
    {
        return new Node(0);
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '*'
            && $node->children()->map(fn ($child) => $child->value())->contains(0);
    }
}
