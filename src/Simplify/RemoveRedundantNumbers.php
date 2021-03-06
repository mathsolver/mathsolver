<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class RemoveRedundantNumbers extends Step
{
    /**
     * Remove all 0's in additions and 1's in multiplication.
     */
    public function handle(Node $node): Node
    {
        // Remove all zeros or ones
        $node->children()
            ->filter(fn ($child) => match ($node->value()) {
                '+' => $child->value() == 0,
                '*' => $child->value() == 1,
                '^' => $node->child(1) === $child && $child->value() == 1,
            })
            ->each(fn ($child) => $node->removeChild($child));

        // Remove the first child if no other children are left
        if ($node->children()->count() === 1) {
            $node = $node->children()->first();
            $node->setParent(null);
        }

        // Return the parent node
        return $node;
    }

    /**
     * Only run in additions.
     */
    public function shouldRun(Node $node): bool
    {
        return in_array($node->value(), ['+', '*', '^'])
            && $node->numericChildren()->count() == 1;
    }
}
