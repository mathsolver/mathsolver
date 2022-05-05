<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class SimplifyFractions extends Step
{
    /**
     * Find the greatest common divisor and divide both integers by it.
     */
    public function handle(Node $node): Node
    {
        $numerator = $node->child(0)->value();
        $denominator = $node->children()->last()->value();

        $greatestCommonDivisor = (int) gmp_gcd($numerator, $denominator);

        // Remove double negative numbers
        if ($numerator < 0 && $denominator < 0) {
            $greatestCommonDivisor = $greatestCommonDivisor * -1;
        }

        $node->child(0)->setValue($numerator / $greatestCommonDivisor);
        $node->children()->last()->setValue($denominator / $greatestCommonDivisor);

        // Check if the denominator equals 1
        if ($node->children()->last()->value() == 1) {
            return new Node($node->child(0)->value());
        }

        // Check if the denominator equals -1
        if ($node->children()->last()->value() == -1) {
            return new Node(-1 * $node->child(0)->value());
        }

        return $node;
    }

    /**
     * Only execute this step when it is a fraction with real numbers.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && is_numeric($node->child(0)->value())
            && is_numeric($node->children()->last()->value());
    }
}
