<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MultiplyFractions extends Step
{
    /**
     * Multiply fractions and numbers.
     */
    public function handle(Node $node): Node
    {
        // find all fractions
        $fractions = $node->children()->filter(fn ($child) => $this->isFraction($child));

        // don't run when the amount of fractions is zero
        if ($fractions->count() === 0) {
            return $node;
        }

        // don't run when there is only one fraction
        if ($fractions->count() === 1 && $node->numericChildren()->count() === 0) {
            return $node;
        }

        $numerator = 1;
        $denominator = 1;

        // multiply the numerator and denominator for each fraction
        foreach ($fractions as $fraction) {
            $numerator = $numerator * $fraction->child(0)->value();
            $denominator = $denominator * $fraction->children()->last()->value();
            $node->removeChild($fraction);
        }

        // multiply the numerator for each whole number, as the denominator is always 1
        foreach ($node->numericChildren() as $number) {
            $numerator = $numerator * $number->value();
            $node->removeChild($number);
        }

        // create a fraction node
        $fraction = new Node('frac');
        $fraction->appendChild(new Node($numerator));
        $fraction->appendChild(new Node($denominator));

        $node->appendChild($fraction);
        return $node;
    }

    /**
     * Only run in multiplications.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '*';
    }

    /**
     * Check if a node is a fraction with real numbers.
     */
    protected function isFraction(Node $node): bool
    {
        return $node->value() === 'frac'
            && $node->numericChildren()->count() === $node->children()->count();
    }
}
