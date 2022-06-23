<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AddFractions extends Step
{
    /**
     * Add fractions (with different denominators) together.
     */
    public function handle(Node $node): Node
    {
        $fraction = new Node('frac');

        $fraction->appendChild($this->getNumerator($node));

        $fraction->appendChild($this->getDenominator($node));

        return $fraction;
    }

    /**
     * Run this function when two fractions are added, or a fraction with a letter/number.
     */
    public function shouldRun(Node $node): bool
    {
        if ($node->value() !== '+') {
            return false;
        }

        $fractionsCount = $node->children()->filter(fn (Node $child) => $child->value() === 'frac')->count();

        return $fractionsCount > 0;
    }

    /**
     * Loop over each fraction and multiply the denominators
     * of the other fractions by the numerator of the current
     * fraction.
     */
    protected function getNumerator(Node $node): Node
    {
        $numerator = new Node('+');

        foreach ($node->children() as $child) {
            $times = $numerator->appendChild(new Node('*'));

            $node->children() // Get all fractions
                ->filter(fn (Node $fraction) => $fraction !== $child) // Filter out current one
                ->map(fn (Node $fraction) => $this->findDenominator($fraction)) // Get the denominators
                ->prepend($this->findNumerator($child)) // Append the current numerator
                ->each(function (Node $factor) use ($times) {
                    $times->appendChild($factor->clone()->wrapInBrackets('*'));
                });
        }

        return $numerator;
    }

    /**
     * Multiply all denominators by eachother to get the new denominator.
     */
    protected function getDenominator(Node $node): Node
    {
        $denominator = new Node('*');

        $node->children()
            ->map(fn (Node $fraction) => $this->findDenominator($fraction))
            ->each(function (Node $factor) use ($denominator) {
                $denominator->appendChild((clone $factor)->wrapInBrackets('*'));
            });

        return $denominator;
    }

    /**
     * Return the numerator if it is a fraction and else just the node.
     */
    protected function findNumerator(Node $fraction): Node
    {
        return $fraction->value() === 'frac'
            ? $fraction->child(0)
            : $fraction;
    }

    /**
     * Return the denominator if it is a fraction and else just 1.
     */
    protected function findDenominator(Node $fraction): Node
    {
        return $fraction->value() === 'frac'
            ? $fraction->child(1)
            : new Node(1);
    }
}
