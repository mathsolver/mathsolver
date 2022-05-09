<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class ExpandBrackets extends Step
{
    /**
     * Expand all brackets with an exponent.
     *
     * For example: (x + 2)^3 -> (x + 2)(x + 2)(x + 2).
     */
    public function handle(Node $node): Node
    {
        $times = new Node('*');

        for ($x = 1; $x <= $node->children()->last()->value(); $x++) {
            $child = tap(unserialize(serialize($node->child(0))))->setParent($times);
            $times->appendChild($child);
        }

        return $times;
    }

    /**
     * Determine whether this function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '^'
            && $node->child(0)->value() === '('
            && is_numeric($node->children()->last()->value())
            && $node->children()->last()->value() > 0
            && floor($node->children()->last()->value()) == $node->children()->last()->value();
    }
}
