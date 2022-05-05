<?php

namespace MathSolver\Fractions;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AddFractions extends Step
{
    /**
     * Add fractions with different denominators together.
     */
    public function handle(Node $node): Node
    {
        // find all fractions and remove then from the parent node
        $fractions = $node->children()
            ->filter(fn ($child) => $this->isFraction($child))
            ->each(fn ($child) => $node->removeChild($child));

        // don't run when the amount of fractions is less than 2
        if ($fractions->count() === 0) {
            return $node;
        }

        // find the least common multiple of all fractions
        $leastCommonMultiple = $this->findLeastCommonMultiple($fractions);

        // convert all fractions to have the same denominator
        $numbers = $fractions->map(function (Node $fraction) use ($leastCommonMultiple) {
            return (int) $fraction->child(0)->value() * ($leastCommonMultiple / $fraction->children()->last()->value());
        });

        // convert all whole numbers to fractions with the correct denominator
        $node->numericChildren()
            ->each(fn ($child) => $node->removeChild($child))
            ->each(fn ($child) => $numbers->push($child->value() * $leastCommonMultiple));

        // create a fraction node
        $fraction = new Node('frac');
        $fraction->appendChild(new Node($numbers->sum()));
        $fraction->appendChild(new Node($leastCommonMultiple));

        // check if there are other terms besides fractions and whole numbers
        if ($node->children()->count() === 0) {
            return $fraction;
        }

        // append the fraction
        $node->appendChild($fraction);
        return $node;
    }

    /**
     * Only run in additions.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '+';
    }

    /**
     * Check if a node is a fraction with real numbers.
     */
    protected function isFraction(Node $node): bool
    {
        return $node->value() === 'frac'
            && $node->numericChildren()->count() === $node->children()->count();
    }

    /**
     * Find the least common multiple of all denominators of all fractions.
     */
    protected function findLeastCommonMultiple(Collection $fractions): int
    {
        $leastCommonMultiple = 1;

        foreach ($fractions as $fraction) {
            $leastCommonMultiple = (int) gmp_lcm(
                $leastCommonMultiple,
                $fraction->children()->last()->value()
            );
        }

        return $leastCommonMultiple;
    }
}
