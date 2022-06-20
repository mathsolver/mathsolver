<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

/**
 * Convert divisions into fractions.
 *
 * `2/5 => frac[2, 5]`
 */
class ParseFractions extends Step
{
    /**
     * Handle this step.
     */
    public function handle(Node $node): Node
    {
        $fraction = new Node('frac');

        $fraction->appendChild($node->child(0));
        $fraction->appendChild($node->child(1));
        return $fraction;
    }

    /**
     * Only run in divisions.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '/';
    }
}
