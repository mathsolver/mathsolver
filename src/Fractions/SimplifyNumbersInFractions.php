<?php

namespace MathSolver\Fractions;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class SimplifyNumbersInFractions extends Step
{
    /**
     * A collection containing all number-nodes.
     */
    public Collection $numbers;

    /**
     * Simplify numbers in fractions.
     *
     * For example:
     * - 2x / 4 => x / 2
     * - (3x + 6) / 9 => (x + 2) / 3
     * - 4x / 4 => x
     */
    public function handle(Node $fraction): Node
    {
        $this->numbers = new Collection();

        $this->findNumbers($fraction->child(0)); // Numerator
        $this->findNumbers($fraction->child(1)); // Denominator

        $gcd = $this->calculateGcd();

        $this->numbers->filter()->each(fn (Node $number) => $number->setValue($number->value() / $gcd));

        if ($fraction->child(1)->value() == 1) {
            return $fraction->child(0);
        }

        return $fraction;
    }

    /**
     * Only run when it is a fraction.
     *
     * When both the numerator and the denominator are
     * an integer, then another function should do the
     * simplification, instead of this function.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && !($node->child(0)->isInt() && $node->child(1)->isInt());
    }

    /**
     * Find all numeric children in this node.
     */
    protected function findNumbers(Node $node): void
    {
        if ($node->isInt()) {
            $this->numbers->push($node);
            return;
        }

        if ($node->value() === '*') {
            $number = $node->children()
                ->filter(fn (Node $factor) => $factor->isInt())
                ->whenEmpty(fn (Collection $collection) => $collection->add(new Node(1)))
                ->first();

            $this->numbers->push($number);

            return;
        }

        if ($node->value() === '+') {
            foreach ($node->children() as $term) {
                $this->findNumbers($term);
            }

            return;
        }

        $this->numbers->push(new Node(1));
    }

    /**
     * Calculate the GCD (Greatest Common Multiple) from all numeric nodes.
     */
    protected function calculateGcd(): int
    {
        return (int) $this->numbers
            ->map(fn (Node $number) => (int) $number->value())
            ->reduce(fn ($gcd, $number) => !is_null($gcd) ? gmp_gcd($gcd, $number) : $number);
    }
}
