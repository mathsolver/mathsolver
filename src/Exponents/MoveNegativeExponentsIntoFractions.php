<?php

namespace MathSolver\Exponents;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MoveNegativeExponentsIntoFractions extends Step
{
    /**
     * Move powers with real numbers and negative exponents into fractions.
     *
     * For example: 3^-1 -> 1/3
     */
    public function handle(Node $node): Node
    {
        // Create a fraction node
        $fraction = new Node('frac');
        $fraction->appendChild(new Node(1));

        // Check if the power is 1
        if ($node->children()->last()->value() == -1) {
            $fraction->appendChild($node->child(0));
            return $fraction;
        }

        // Append the power to the fraction
        $power = $fraction->appendChild(new Node('^'));
        $power->appendChild($node->child(0));
        $power->appendChild(new Node($node->children()->last()->value() * -1));

        return $fraction;
    }

    /**
     * Determine when this function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '^'
            && is_numeric($node->child(1)->value())
            && $node->child(1)->value() < 0;
    }
}
