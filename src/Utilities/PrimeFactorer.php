<?php

namespace MathSolver\Utilities;

class PrimeFactorer
{
    /**
     * Find all prime factors of an integer.
     */
    public static function run(int $integer): array
    {
        $factors = [];
        $currentDivider = 2;

        while ($integer !== 1) {
            if (is_int($integer / $currentDivider)) {
                $factors[] = $currentDivider;
                $integer = $integer / $currentDivider;
            } else {
                $currentDivider++;
            }
        }

        return $factors;
    }
}
