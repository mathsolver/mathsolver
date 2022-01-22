<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;

abstract class Step
{
    /**
     * Handle the actual execution of the simplifying process.
     */
    abstract public function handle(Node $node): Node|Collection;

    /**
     * Determine whether the function should run.
     */
    abstract public function shouldRun(Node $node): bool;

    /**
     * A facade for running simplify steps.
     */
    public static function run(Node $node): Node|Collection
    {
        $node->setChildren($node->children()->map(fn ($child) => self::run($child))->flatten());

        if (!(new (get_called_class()))->shouldRun($node)) { /** @phpstan-ignore-line */
            return $node;
        }

        return (new (get_called_class()))->handle($node); /** @phpstan-ignore-line */
    }
}
