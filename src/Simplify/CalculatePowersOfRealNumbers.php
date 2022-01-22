<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class CalculatePowersOfRealNumbers extends Step
{
    /**
     * Calculate the powers of real numbers, such as 9^3 -> 729.
     */
    public function handle(Node $node): Node
    {
        $base = $node->children()->first()->value();
        $exponent = $node->children()->last()->value();

        if ($base < 0 && $exponent % 2 === 0) {
            return new Node(-1 * pow($base, $exponent));
        }

        return new Node(pow($base, $exponent));
    }

    /**
     * Only run this function when all children are numbers, and the exponent is a whole number.
     */
    public function shouldRun(Node $node): bool
    {
        if ($node->value() !== '^') {
            return false;
        }

        if (!is_numeric($node->children()->first()->value())) {
            return false;
        }

        if (!is_numeric($node->children()->last()->value())) {
            return false;
        }

        return floor($node->children()->last()->value()) === (float) $node->children()->last()->value();
    }
}
