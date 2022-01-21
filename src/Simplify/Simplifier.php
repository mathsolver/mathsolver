<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class Simplifier
{
    /**
     * A list of all steps to perform.
     */
    protected static array $steps = [
        ExpandBrackets::class,
        RemoveBracketsWithPlus::class,
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
        foreach (self::$steps as $step) {
            $tree = (new $step())->run($tree);
        }

        return $tree;
    }
}
