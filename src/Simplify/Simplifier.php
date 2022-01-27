<?php

namespace MathSolver\Simplify;

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
        ExpandBrackets::class,
        ConvertBrokenExponentsIntoRoots::class,
        RemoveBrackets::class,
        RemoveBracketsWithPlus::class,
        SimplifyRoots::class,
        CalculatePowersOfRealNumbers::class,
        MultiplyRealNumbers::class,
        MultiplyLikeTerms::class,
        AddLikeTerms::class,
        ExponentOfZero::class,
        MultiplyByZero::class,
        AddRealNumbers::class,
    ];

    /**
     * Simplify the expression as much as possible.
     */
    public static function run(Node $tree): Node
    {
        $oldTree = '';

        while (TreeToStringConverter::run($tree) !== $oldTree) {
            $oldTree = TreeToStringConverter::run($tree);

            foreach (self::$steps as $step) {
                $tree = $step::run($tree);
            }
        }

        return $tree;
    }
}
