<?php

namespace MathSolver\Functions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class GenerateRandomNumber extends Step
{
    /**
     * Generate a random number between a minium (included) and a maximum (included).
     */
    public function handle(Node $node): Node
    {
        $min = (int) $node->child(0)->value();
        $max = (int) $node->children()->last()->value();

        return new Node(rand($min, $max));
    }

    /**
     * Only run when there are real numbers.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'rand'
        && is_numeric($node->child(0)->value())
        && is_numeric($node->children()->last()->value());
    }
}
