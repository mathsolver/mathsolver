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
    public static function run(Node $node): Collection|Node
    {
        // Run this function resursively for all children
        $node->setChildren($node->children()->map(fn ($child) => self::run($child))->flatten());

        // Determine whether this function should run for this node
        if (!(new (get_called_class()))->shouldRun($node)) {
            return $node;
        }

        // Execute the function
        return (new (get_called_class()))->handle($node);
    }
}
