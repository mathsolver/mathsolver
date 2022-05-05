<?php

namespace MathSolver\Utilities;

use Illuminate\Support\Collection;

abstract class Step
{
    /**
     * Handle the actual execution of the process.
     *
     * @return Collection<Node>|Node
     */
    abstract public function handle(Node $node): Node|Collection;

    /**
     * Determine whether the function should run.
     */
    abstract public function shouldRun(Node $node): bool;

    /**
     * A facade for running steps recursively.
     *
     * @return Collection<Node>|Node
     */
    public static function run(Node $node): Node|Collection
    {
        // Run this function resursively for all children
        $node->setChildren($node->children()->map(fn ($child) => self::run($child))->flatten());

        // Determine whether this function should run for this node
        if (!(new (get_called_class()))->shouldRun($node)) {
            return $node;
        }

        // Execute the function
        $parent = $node->parent();
        $result = (new (get_called_class()))->handle($node);
        return self::cleanOutput($parent, $result);
    }

    protected static function cleanOutput(Node|null $parent, Node|Collection $result): Node|Collection
    {
        // Only run when the $result is a Node
        if (!$result instanceof Node) {
            return $result;
        }

        // If the result and the parent are both a multiplication,
        // the children of the $result should be returned so it
        // doesn't end up with a nested multiplication.
        if ($result->value() === '*' && $parent?->value() === '*') {
            return $result->children();
        }

        // If the result and the parent are both a addition,
        // the children of the $result should be returned so it
        // doesn't end up with a nested addition.
        if ($result->value() === '+' && $parent?->value() === '+') {
            return $result->children();
        }

        // If the result is an addition (+) or a multiplication (*)
        // and it has only one child, then just return that one
        // child.
        if (($result->value() === '*' || $result->value() === '+') && $result->children()->count() === 1) {
            return tap($result->child(0))->setParent(null);
        }

        // Return the final result
        return $result;
    }
}
