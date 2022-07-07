<?php

namespace MathSolver\Fractions;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class SimplifyNumbersInFractions extends Step
{
    public function handle(Node $fraction): Node
    {
        $numbers = new Collection();

        // Numerator
        if ($fraction->child(0)->isInt()) {
            $numbers->push($fraction->child(0));
        } elseif ($fraction->child(0)->value() === '*') {
            $numbers->push($fraction->child(0)->children()->filter(fn (Node $factor) => $factor->isInt())->first());
        } elseif ($fraction->child(0)->value() === '+') {
            foreach ($fraction->child(0)->children() as $term) {
                if ($term->isInt()) {
                    $numbers->push($term);
                } elseif ($term->value() === '*') {
                    $numbers->push($term->children()->filter(fn (Node $factor) => $factor->isInt())->first());
                }
            }
        }

        $numeratorCount = $numbers->count();

        // Denominator
        if ($fraction->child(1)->isInt()) {
            $numbers->push($fraction->child(1));
        } elseif ($fraction->child(1)->value() === '*') {
            $numbers->push($fraction->child(1)->children()->filter(fn (Node $factor) => $factor->isInt())->first());
        } elseif ($fraction->child(1)->value() === '+') {
            foreach ($fraction->child(1)->children() as $term) {
                if ($term->isInt()) {
                    $numbers->push($term);
                } elseif ($term->value() === '*') {
                    $numbers->push($term->children()->filter(fn (Node $factor) => $factor->isInt())->first());
                }
            }
        }

        $denominatorCount = $numbers->count() - $numeratorCount;

        if ($numeratorCount === 0 || $denominatorCount === 0) {
            return $fraction;
        }

        $gcd = (int) $numbers
            ->filter()
            ->map(fn (Node $number) => (int) $number->value())
            ->reduce(fn ($gcd, $number) => !is_null($gcd) ? gmp_gcd($gcd, $number) : $number);

        $numbers->filter()->each(fn (Node $number) => $number->setValue($number->value() / $gcd));

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
}
