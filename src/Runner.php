<?php

namespace MathSolver;

use Illuminate\Support\Str;
use MathSolver\Simplify\SortFactors;
use MathSolver\Simplify\SortTerms;
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
        Derivative\CoefficientRule::class,
        Derivative\ConstantRule::class,
        Derivative\MonoVariableRule::class,
        Derivative\PowerRule::class,
        Derivative\SumRule::class,
        Simplify\AddFractions::class,
        Simplify\AddLikeTerms::class,
        Simplify\AddRealNumbers::class,
        Simplify\CalculateLogarithms::class,
        Simplify\CalculatePowersOfRealNumbers::class,
        Simplify\ConvertBrokenExponentsIntoRoots::class,
        Simplify\ConvertDecimalsIntoFractions::class,
        Simplify\ExpandBrackets::class,
        Simplify\ExpandBracketsWithPlus::class,
        Simplify\ExponentOfZero::class,
        Simplify\GenerateRandomNumber::class,
        Simplify\MoveNegativeExponentsIntoFractions::class,
        Simplify\MultiplyByZero::class,
        Simplify\MultiplyFractions::class,
        Simplify\MultiplyLikeFactors::class,
        Simplify\MultiplyRealNumbers::class,
        Simplify\RemoveBrackets::class,
        Simplify\RemoveRedundantNumbers::class,
        Simplify\SimplifyFractions::class,
        Simplify\SimplifyRoots::class,
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
