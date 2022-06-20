<?php

namespace MathSolver;

use Illuminate\Support\Str;
use MathSolver\Sorting\SortFactors;
use MathSolver\Sorting\SortTerms;
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
        Arithmetic\MultiplyByZero::class,
        Arithmetic\MultiplyRealNumbers::class,
        Derivative\CoefficientRule::class,
        Derivative\ConstantRule::class,
        Derivative\MonoVariableRule::class,
        Derivative\PowerRule::class,
        Derivative\RootRule::class,
        Derivative\SumRule::class,
        Exponents\AppendPowersToBrackets::class,
        Exponents\CalculateLogarithms::class,
        Exponents\CalculatePowersOfRealNumbers::class,
        Exponents\ExponentOfZero::class,
        Exponents\MoveNegativeExponentsIntoFractions::class,
        Exponents\MultiplyExponents::class,
        Exponents\MultiplyLikeFactorsAndConvertBrokenExponentsIntoRoots::class,
        Exponents\SimplifyRoots::class,
        Fractions\AddFractions::class,
        Fractions\ConvertDecimalsIntoFractions::class,
        Fractions\MultiplyFractions::class,
        Fractions\ParseFractions::class,
        Fractions\RemoveNestedFractionsInDenominator::class,
        Fractions\RemoveNestedFractionsInNumerator::class,
        Fractions\SimplifyFractions::class,
        Functions\GenerateRandomNumber::class,
        Simplify\AddLikeTerms::class,
        Simplify\ExpandBrackets::class,
        Simplify\ExpandBracketsWithPlus::class,
        Simplify\RemoveBrackets::class,
        Simplify\RemoveRedundantNumbers::class,
    ];

    /**
     * Run all steps to simplify, differentiate and solve.
     */
    public static function run(Node $tree, bool $mathjax = false): array
    {
        $steps = [];
        $oldTree = '';

        $tree = self::sortTree($tree);

        while (TreeToStringConverter::run($tree) !== $oldTree) {
            $oldTree = TreeToStringConverter::run($tree);

            foreach (self::$steps as $step) {
                $previousTree = TreeToStringConverter::run($tree);
                $tree = self::sortTree($step::run($tree));

                if (TreeToStringConverter::run($tree) !== $previousTree) {
                    $steps[] = [
                        'type' => 'simplify',
                        'name' => (string) Str::of($step)->classBasename()->headline()->lower()->ucfirst(),
                        'result' => TreeToStringConverter::run($tree, $mathjax),
                    ];
                }
            }
        }

        return ['result' => $tree, 'steps' => $steps];
    }

    /**
     * Sort all factors and terms in a tree.
     */
    protected static function sortTree(Node $tree): Node
    {
        return SortTerms::run(SortFactors::run($tree));
    }
}
