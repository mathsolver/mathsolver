<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class MultiplyByZero
{
    /**
     * Replace all multiplications with a zero with a zero.
     *
     * For example: 7 * 0 -> 0, 0 * y -> 0.
     */
    public function run(Node $parentNode): Node
    {
        $parentNode->setChildren($parentNode->children()->map(fn ($child) => $this->run($child)));

        if ($parentNode->value() !== '*') {
            return $parentNode;
        }

        if ($parentNode->children()->map(fn ($child) => $child->value())->contains(0)) {
            return new Node(0);
        }

        return $parentNode;
    }
}