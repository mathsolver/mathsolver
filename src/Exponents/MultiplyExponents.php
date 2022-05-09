<?php

namespace MathSolver\Exponents;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MultiplyExponents extends Step
{
    /**
     * Multiply exponents that are stacked up.
     *
     * For example: (x^2)^-1 => x^(2*-1)
     */
    public function handle(Node $node): Node
    {
        // Find the base, and the first and second exponent
        $base = $node->child(0)->child(0)->child(0);
        $outsideExponent = $node->child(1);
        $insideExponent = $node->child(0)->child(0)->child(1);

        // Create a new node for the power and append the old base
        $power = new Node('^');
        $power->appendChild($base);

        // If both exponents are numeric, multiply them directly. If
        // not, create a new node to multiply them
        if (is_numeric($outsideExponent->value()) && is_numeric($insideExponent->value())) {
            $power->appendChild(new Node($outsideExponent->value() * $insideExponent->value()));
        } else {
            $brackets = $power->appendChild(new Node('('));
            $times = $brackets->appendChild(new Node('*'));
            $times->appendChild($insideExponent);
            $times->appendChild($outsideExponent);
        }

        // Return the new power
        return $power;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '^'
            && $node->child(0)->value() === '('
            && $node->child(0)->child(0)->value() === '^';
    }
}
