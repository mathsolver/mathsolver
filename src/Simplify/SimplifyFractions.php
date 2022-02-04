<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class SimplifyFractions extends Step
{
    /**
     * Find the greatest common divisor and divide both integers by it.
     */
    public function handle(Node $node): Node
    {
        $numerator = $node->children()->first()->value();
        $denominator = $node->children()->last()->value();

        $greatestCommonDivisor = (int) gmp_gcd($numerator, $denominator);

        // remove double negative numbers
        if ($numerator < 0 && $denominator < 0) {
            $greatestCommonDivisor = $greatestCommonDivisor * -1;
        }

        $node->children()->first()->setValue($numerator / $greatestCommonDivisor);
        $node->children()->last()->setValue($denominator / $greatestCommonDivisor);

        // check if the denominator equals 1
        if ($node->children()->last()->value() == 1) {
            return new Node($node->children()->first()->value());
        }

        // check if the denominator equals -1
        if ($node->children()->last()->value() == -1) {
            return new Node(-1 * $node->children()->first()->value());
        }

        return $node;
    }

    /**
     * Only execute this step when it is a fraction with real numbers.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && is_numeric($node->children()->first()->value())
            && is_numeric($node->children()->last()->value());
    }
}
