<?php

namespace MathSolver\Derivative;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class ConstantRule extends Step
{
    use DifferentiateWithRespect;

    /**
     * Check if it is a constant function.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'deriv'
            && !$node->child(0)->contains($this->respect($node));
    }

    /**
     * Return 0.
     */
    public function handle(Node $node): Node
    {
        return new Node(0);
    }
}
