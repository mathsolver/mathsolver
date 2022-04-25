<?php

namespace MathSolver\Derivatives;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MonoVariableRule extends Step
{
    /**
     * Run this function when the inside of the `deriv()` function is `x`.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'deriv'
            && $node->child(0)->value() === 'x';
    }

    /**
     * Return 1.
     */
    public function handle(Node $node): Node
    {
        return new Node(1);
    }
}
