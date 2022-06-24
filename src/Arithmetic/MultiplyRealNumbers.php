<?php

namespace MathSolver\Arithmetic;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MultiplyRealNumbers extends Step
{
    /**
     * Multiply all real numbers.
     *
     * For example 2 * 3 * 6 -> 36, 2 * 4 * x -> 8 * x.
     */
    public function handle(Node $node): Node
    {
        $total = 1;

        foreach ($node->numericChildren() as $child) {
            $node->removeChild($child);

            $total *= $child->value();
        }

        $node->appendChild(new Node($total), true);

        return $node;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '*'
            && $node->numericChildren()->count() > 0;
    }
}
