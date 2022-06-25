<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MultiplyFractions extends Step
{
    use FindValues;

    /**
     * Multiply fractions (with different denominators).
     */
    public function handle(Node $node): Node
    {
        $fraction = new Node('frac');

        $numerator = $fraction->appendChild(new Node('*'));
        $denominator = $fraction->appendChild(new Node('*'));

        foreach ($node->children() as $child) {
            $numerator->appendChild($this->findNumerator($child)->clone()->wrapInBrackets('*'));
            $denominator->appendChild($this->findDenominator($child)->clone()->wrapInBrackets('*'));
        }

        return $fraction;
    }

    /**
     * Only run in multiplications.
     */
    public function shouldRun(Node $node): bool
    {
        if ($node->value() !== '*') {
            return false;
        }

        $fractionsCount = $node->children()->filter(fn (Node $child) => $child->value() === 'frac')->count();

        return $fractionsCount > 0;
    }
}
