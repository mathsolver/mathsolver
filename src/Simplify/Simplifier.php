<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Str;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\TreeToStringConverter;

class Simplifier
{
    /**
     * A list of all steps to perform.
     *
     * @var array<class-string<Step>>
     */
    protected static array $steps = [
        AddFractions::class,
        AddLikeTerms::class,
        AddRealNumbers::class,
        CalculatePowersOfRealNumbers::class,
        ConvertBrokenExponentsIntoRoots::class,
        ExpandBrackets::class,
        ExpandBracketsWithPlus::class,
        ExponentOfZero::class,
        MoveNegativeExponentsIntoFractions::class,
        MultiplyByZero::class,
        MultiplyFractions::class,
        MultiplyLikeFactors::class,
        MultiplyRealNumbers::class,
        RemoveBrackets::class,
        SimplifyFractions::class,
        SimplifyRoots::class,
    ];

    /**
     * Simplify the expression as much as possible.
     */
    public static function run(Node $tree): array
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
                        'result' => TreeToStringConverter::run($tree, $mathjax = true),
                    ];
                }
            }
        }

        return ['tree' => $tree, 'steps' => $steps];
    }

    /**
     * Sort all factors and terms in a tree.
     */
    protected static function sortTree(Node $tree): Node
    {
        return SortTerms::run(SortFactors::run($tree));
    }
}
