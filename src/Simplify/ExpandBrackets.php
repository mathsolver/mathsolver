<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class ExpandBrackets extends Step
{
    /**
     * Expand all brackets with an exponent.
     *
     * For example: (x + 2)^3 -> (x + 2)(x + 2)(x + 2).
     */
    public function handle(Node $node): Node
    {
        if (!$this->shouldExecute($node)) {
            return $this->removeDoubleTimes($node);
        }

        $times = new Node('*');

        for ($x = 1; $x <= $node->children()->last()->value(); $x++) {
            $times->appendChild(tap(unserialize(serialize($node->children()->first())))->setParent($times));
        }

        return $times;
    }

    /**
     * This is always true, because this class does its own check.
     */
    public function shouldRun(Node $node): bool
    {
        return true;
    }

    /**
     * Determine whether the function should run.
     */
    protected function shouldExecute(Node $node): bool
    {
        return $node->value() === '^'
            && $node->children()->first()->value() === '('
            && is_numeric($node->children()->last()->value())
            && $node->children()->last()->value() > 0
            && floor($node->children()->last()->value()) == $node->children()->last()->value();
    }

    /**
     * Remove a nested times in the math tree.
     */
    protected function removeDoubleTimes(Node $node): Node
    {
        if ($node->value() !== '*') {
            return $node;
        }

        $nestedTimeses = $node->children()->filter(fn ($child) => $child->value() === '*');

        $nestedTimeses->each(function ($nestedTimes) use ($node) {
            $nestedTimes->children()->each(function ($nestedTimesChild) use ($node) {
                $node->appendChild(tap(unserialize(serialize($nestedTimesChild)))->setParent($node));
            });

            $node->removeChild($nestedTimes);
        });

        $node->setChildren($node->children()->values());

        return $node;
    }
}
