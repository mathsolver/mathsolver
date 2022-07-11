<?php

namespace MathSolver\Fractions;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class SimplifyNumbersInFractions extends Step
{
    public Collection $numbers;

    public function handle(Node $fraction): Node
    {
        $this->numbers = new Collection();

        // Numerator
        $this->findNumbers($fraction->child(0));

        // Denominator
        $this->findNumbers($fraction->child(1));

        $gcd = (int) $this->numbers
            ->filter()
            ->map(fn (Node $number) => (int) $number->value())
            ->reduce(fn ($gcd, $number) => !is_null($gcd) ? gmp_gcd($gcd, $number) : $number);

        $this->numbers->filter()->each(fn (Node $number) => $number->setValue($number->value() / $gcd));

        if ($fraction->child(1)->value() == 1) {
            return $fraction->child(0);
        }

        return $fraction;
    }

    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && !($node->child(0)->isInt() && $node->child(1)->isInt());
    }

    protected function findNumbers(Node $node)
    {
        if ($node->isInt()) {
            $this->numbers->push($node);
        } elseif ($node->value() === '*') {
            $this->numbers->push($node->children()->filter(fn (Node $factor) => $factor->isInt())->whenEmpty(fn (Collection $collection) => $collection->add(new Node(1)))->first());
        } elseif ($node->value() === '+') {
            foreach ($node->children() as $term) {
                if ($term->isInt()) {
                    $this->numbers->push($term);
                } elseif ($term->value() === '*') {
                    $this->numbers->push($term->children()->filter(fn (Node $factor) => $factor->isInt())->whenEmpty(fn (Collection $collection) => $collection->add(new Node(1)))->first());
                } else {
                    $this->numbers->push(new Node(1));
                }
            }
        } else {
            $this->numbers->push(new Node(1));
        }
    }
}
