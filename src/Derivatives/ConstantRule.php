<?php

namespace MathSolver\Derivatives;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class ConstantRule extends Step
{
    /**
     * Check if it is a constant function.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'deriv'
            && $node->child(0)->isNumeric();
    }

    /**
     * Return 0.
     */
    public function handle(Node $node): Node
    {
        return new Node(0);
    }
}
