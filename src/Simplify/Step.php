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
     * A facade for running simplify steps.
     */
    public static function run(Node $node): Node|Collection
    {
        return (new (get_called_class()))->handle($node); /** @phpstan-ignore-line */
    }
}
