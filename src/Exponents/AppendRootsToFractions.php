<?php

namespace MathSolver\Exponents;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AppendRootsToFractions extends Step
{
    /**
     * Append outer roots to the numerator and denominator.
     *
     * For example: sqrt[1/9] => sqrt[1] / sqrt[9].
     */
    public function handle(Node $root): Node
    {
        // Find degree, numerator and denominator
        $degree = $root->child(1);
        $numerator = $root->child(0)->child(0);
        $denominator = $root->child(0)->child(1);

        // Create a new fraction
        $fraction = new Node('frac');

        // Append the new numerator
        $root = $fraction->appendChild(new Node('root'));
        $root->appendChild($numerator->clone());
        $root->appendChild($degree->clone());

        // Append the new denominator
        $root = $fraction->appendChild(new Node('root'));
        $root->appendChild($denominator->clone());
        $root->appendChild($degree->clone());

        // Return the new fraction
        return $fraction;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'root' && $node->child(0)->value() === 'frac';
    }
}
