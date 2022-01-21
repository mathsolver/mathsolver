<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class ExponentOfZero
{
    /**
     * Replace all powers with an exponent of zero by a one.
     *
     * For example: 6^0 -> 1, x^0 -> 1.
     */
    public function run(Node $parentNode): Node
    {
        $parentNode->setChildren($parentNode->children()->map(fn ($child) => $this->run($child)));

        if ($parentNode->value() !== '^') {
            return $parentNode;
        }

        if ($parentNode->children()->last()->value() == 0) {
            return new Node(1);
        }

        return $parentNode;
    }
}
