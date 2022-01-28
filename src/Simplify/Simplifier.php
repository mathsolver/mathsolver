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
        CalculatePowersOfRealNumbers::class,
        ConvertBrokenExponentsIntoRoots::class,
        ExpandBrackets::class,
        MultiplyRealNumbers::class,
        RemoveBrackets::class,
        RemoveBracketsWithPlus::class,
        SimplifyRoots::class,
        MultiplyLikeTerms::class,
        AddLikeTerms::class,
        ExponentOfZero::class,
        MultiplyByZero::class,
        AddRealNumbers::class,
    ];

    /**
     * Simplify the expression as much as possible.
     */
    public static function run(Node $tree, bool $withSteps = false): array|Node
    {
        $steps = [];
        $oldTree = '';

        while (TreeToStringConverter::run($tree) !== $oldTree) {
            $oldTree = TreeToStringConverter::run($tree);

            foreach (self::$steps as $step) {
                $previousTree = TreeToStringConverter::run($tree);
                $tree = $step::run($tree);

                if (TreeToStringConverter::run($tree) !== $previousTree) {
                    $steps[] = [
                        'name' => (string) Str::of($step)->classBasename()->headline()->lower()->ucfirst(),
                        'result' => TreeToStringConverter::run($tree, $mathjax = true),
                    ];
                }
            }
        }

        return $withSteps
            ? ['tree' => $tree, 'steps' => $steps]
            : $tree;
    }
}
