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
        // Find all numbers and add them up
        $total = (float) $node->numericChildren()
            ->each(fn ($child) => $node->removeChild($child))
            ->reduce(fn ($total, $number) => $number->value() + $total, 0);

        // Check if all children were numbers
        if ($node->children()->count() === 0) {
            return new Node($total);
        }

        // Add the total (if it is not 0)
        if ($total !== 0) {
            $node->appendChild(new Node($total));
        }

        // Return the plus node
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
