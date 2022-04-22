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
            ->filter(fn ($child) => $node->value() === '+' ? $child->value() == 0 : $child->value() == 1)
            ->each(fn ($child) => $node->removeChild($child));

        // Return a zero or one if no other children are left
        if ($node->children()->count() === 0) {
            return $node->value() === '+' ? new Node(0) : new Node(1);
        }

        // Remove the first child if no other children are left
        if ($node->children()->count() === 1) {
            $node = $node->child(0);
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
        return $node->value() === '+' || $node->value() === '*' || $node->value() === '^';
    }
}
