<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class MultiplyRealNumbers extends Step
{
    /**
     * Multiply all real numbers.
     *
     * For example 2 * 3 * 6 -> 36, 2 * 4 * x -> 8 * x.
     */
    public function handle(Node $node): Node
    {
        $total = $node->numericChildren()
            ->each(fn ($child) => $node->removeChild($child))
            ->reduce(fn ($total, $number) => $number->value() * $total, 1);

        if ($node->nonNumericChildren()->count() === 0) {
            return new Node($total);
        }

        if ($total !== 1) {
            $node->appendChild(new Node($total), $top = true);
        }

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
