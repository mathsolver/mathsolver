<?php

namespace MathSolver;

use Illuminate\Support\Str;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\TreeToStringConverter;

class Runner
{
    /**
     * A list of all steps to perform.
     *
     * @var array<class-string<Step>>
     */
    protected static array $steps = [
        Arithmetic\AddRealNumbers::class,
        Arithmetic\CalculatePowersOfRealNumbers::class,
        Arithmetic\DivideRealNumbers::class,
        Arithmetic\MultiplyByZero::class,
        Arithmetic\MultiplyRealNumbers::class,
        Derivative\CoefficientRule::class,
        Derivative\ConstantRule::class,
        Derivative\MonoVariableRule::class,
        Derivative\PowerRule::class,
        Derivative\RootRule::class,
        Derivative\SumRule::class,
        Exponents\AppendExponentsToFractions::class,
        Exponents\AppendPowersToBrackets::class,
        Exponents\AppendRootsToFractions::class,
        Exponents\CalculateLogarithms::class,
        Exponents\ExponentOfZero::class,
        Exponents\MoveNegativeExponentsIntoFractions::class,
        Exponents\MultiplyExponents::class,
        Exponents\MultiplyLikeFactorsAndConvertBrokenExponentsIntoRoots::class,
        Exponents\SimplifyRoots::class,
        Fractions\AddFractions::class,
        Fractions\MultiplyFractions::class,
        Fractions\ParseFractions::class,
        Fractions\RemoveNestedFractionsInDenominator::class,
        Fractions\RemoveNestedFractionsInNumerator::class,
        Fractions\SimplifyFractions::class,
        Functions\GenerateRandomNumber::class,
        Functions\RoundNumbers::class,
        Simplify\AddLikeTerms::class,
        Simplify\ExpandBrackets::class,
        Simplify\ExpandBracketsWithPlus::class,
        Simplify\RemoveBrackets::class,
        Simplify\RemoveRedundantNumbers::class,
        Sorting\SortFactors::class,
        Sorting\SortTerms::class,
    ];

    /**
     * Run all steps to simplify, differentiate and solve.
     */
    public static function run(Node $tree, bool $mathjax = false): array
    {
        $steps = [];
        $oldTree = '';

        while (serialize($tree) !== $oldTree) {
            $oldTree = serialize($tree);

            foreach (self::$steps as $step) {
                $previousTree = serialize($tree);
                $tree = $step::run($tree);

                if (serialize($tree) !== $previousTree) {
                    $steps[] = [
                        'name' => (string) Str::of($step)->classBasename()->headline()->lower()->ucfirst(),
                        'docs' => $step::$docs,
                        'result' => TreeToStringConverter::run($tree, $mathjax),
                    ];
                }
            }
        }

        return ['result' => $tree, 'steps' => $steps];
    }
}
