<?php

namespace MathSolver\Arithmetic;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AddRealNumbers extends Step
{
    /**
     * Add all real numbers together. For example 9 + 5 -> 14.
     */
    public function handle(Node $node): Node
    {
        $total = 0;

        foreach ($node->numericChildren() as $child) {
            $node->removeChild($child);

            $total += $child->value();
        }

        $node->appendChild(new Node($total));

        return $node;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '+' && $node->numericChildren()->count() > 1;
    }
}
