<?php

namespace MathSolver\Functions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class RoundNumbers extends Step
{
    /**
     * Round numbers.
     */
    public function handle(Node $node): Node
    {
        // First value is numeric and no rounding given: return the first value
        if (is_null($node->child(1)) && is_numeric($node->child(0)->value())) {
            return new Node($node->child(0)->value());
        }

        // Rounding precision is not an integer: do nothing
        if (!is_numeric($node->child(1)->value()) || (float) $node->child(1)->value() !== floor($node->child(1)->value())) {
            return $node;
        }

        // Round the number by the given precision
        return new Node(round($node->child(0)->value(), $node->child(1)->value()));
    }

    /**
     * Only run in `calc` functions when the first value is numeric.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'calc' && is_numeric($node->child(0)->value());
    }
}
