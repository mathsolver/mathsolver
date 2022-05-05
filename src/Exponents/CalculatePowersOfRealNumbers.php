<?php

namespace MathSolver\Exponents;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class CalculatePowersOfRealNumbers extends Step
{
    /**
     * Calculate the powers of real numbers, such as 9^3 -> 729.
     */
    public function handle(Node $node): Node
    {
        $base = $node->child(0)->value() === '('
            ? $node->child(0)->child(0)->value()
            : $node->child(0)->value();

        $exponent = $node->children()->last()->value();

        if ($base < 0 && $exponent % 2 === 0 && $node->child(0)->value() !== '(') {
            return new Node(-1 * pow($base, $exponent));
        }

        return new Node(pow($base, $exponent));
    }

    /**
     * Only run this function when all children are numbers, and the exponent is a whole number.
     */
    public function shouldRun(Node $node): bool
    {
        // check if it is a power
        if ($node->value() !== '^') {
            return false;
        }

        // check if the base is a real number
        if (!is_numeric($node->child(0)->value())) {
            // check if it is a bracket with a nested number
            if ($node->child(0)->value() !== '(' || !is_numeric($node->child(0)->child(0)->value())) {
                return false;
            }
        }

        // check if the exponent is a real and positive number
        if (!is_numeric($node->children()->last()->value()) || $node->children()->last()->value() < 0) {
            return false;
        }

        // check if both numbers are whole numbers
        return floor($node->children()->last()->value()) === (float) $node->children()->last()->value();
    }
}
