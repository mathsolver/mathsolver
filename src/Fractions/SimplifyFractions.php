<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Fraction;
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

        return (new Fraction($numerator, $denominator))->simplify()->node();
    }

    /**
     * Only execute this step when it is a fraction with real numbers.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && is_numeric($node->child(0)->value())
            && is_numeric($node->child(1)->value());
    }
}
