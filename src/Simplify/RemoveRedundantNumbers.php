<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class RemoveRedundantNumbers extends Step
{
    /**
     * Remove all 0's in additions.
     */
    public function handle(Node $node): Node
    {
        // Remove all zeros
        $node->children()
            ->filter(fn ($child) => $child->value() == 0)
            ->each(fn ($child) => $node->removeChild($child));

        // Return a zero is no other children are left
        if ($node->children()->count() === 0) {
            return new Node(0);
        }

        // Remove the first child if no other children are left
        if ($node->children()->count() === 1) {
            $node = $node->children()->first();
            $node->setParent(null);
        }

        // Return the + node
        return $node;
    }

    /**
     * Only run in additions.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '+';
    }
}
