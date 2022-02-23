<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class GenerateRandomNumber extends Step
{
    /**
     * Generate a random number between a minium (included) and a maximum (included).
     */
    public function handle(Node $node): Node
    {
        $min = (int) $node->children()->first()->value();
        $max = (int) $node->children()->last()->value();

        return new Node(rand($min, $max));
    }

    /**
     * Only run when there are real numbers.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'rand'
        && is_numeric($node->children()->first()->value())
        && is_numeric($node->children()->last()->value());
    }
}
