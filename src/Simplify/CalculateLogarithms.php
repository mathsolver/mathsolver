<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class CalculateLogarithms extends Step
{
    /**
     * Calculate the logarithm of a number.
     */
    public function handle(Node $node): Node
    {
        $number = $node->child(0)->value();
        $base = $node->children()->last()->value();

        // use a log base 10 when there is no base given
        if ($node->children()->count() === 1) {
            $base = 10;
        }

        $result = log($number, $base);

        // check if it is an exact value
        if (round($result, 4) === $result) {
            return new Node($result);
        }

        return $node;
    }

    /**
     * Determine whether this function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'log';
    }
}
