<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class ConvertDecimalsIntoFractions extends Step
{
    /**
     * Only run if it is a number and the number is not whole.
     */
    public function shouldRun(Node $node): bool
    {
        return is_numeric($node->value())
            && floor($node->value()) !== (float) $node->value();
    }

    /**
     * Create a fraction and find the numerator and denominator.
     *
     * For example: `0.25 => 25/100`
     */
    public function handle(Node $node): Node
    {
        $fraction = new Node('frac');

        $amountOfDecimals = strlen(str($node->value())->explode('.')->last());

        $fraction->appendChild(new Node(pow(10, $amountOfDecimals) * $node->value()));
        $fraction->appendChild(new Node(pow(10, $amountOfDecimals)));

        return $fraction;
    }
}
