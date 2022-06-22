<?php

namespace MathSolver\Arithmetic;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class DivideRealNumbers extends Step
{
    /**
     * Divide the numbers.
     */
    public function handle(Node $node): Node
    {
        return new Node($node->child(0)->value() / $node->child(1)->value());
    }

    /**
     * Only run this function when it is in a `calc` function.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && $node->isChildOf('calc')
            && is_numeric($node->child(0)->value())
            && is_numeric($node->child(1)->value());
    }
}
