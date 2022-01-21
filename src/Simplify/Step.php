<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

abstract class Step
{
    /**
     * Handle the actual execution of the simplifying process.
     */
    abstract public function handle(Node $node): Node;

    /**
     * A facade for running simplify steps.
     */
    public static function run(Node $node): Node
    {
        return (new (get_called_class()))->handle($node); /** @phpstan-ignore-line */
    }
}
