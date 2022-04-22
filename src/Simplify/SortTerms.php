<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class SortTerms extends Step
{
    /**
     * Sort terms, for example y+x -> x+y.
     */
    public function handle(Node $node): Node
    {
        $node->setChildren(
            $node->children()
                ->sortByDesc(fn ($child) => $this->getValue($child))
                ->values()
        );

        return $node;
    }

    /**
     * Only run when the node is a +.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '+';
    }

    /**
     * Get the value of the current node.
     */
    protected function getValue(Node $node): int
    {
        // check if it is a number
        if (is_numeric($node->value())) {
            return 10;
        }

        // check if it is a letter
        if (preg_match('/[a-z]/', $node->value())) {
            return ord($node->value()) * -1 + 200;
        }

        // check for multiplications
        if ($node->value() === '*') {
            $amountOfLetterChildren = $node->children()->filter(fn ($child) => preg_match('/[a-z]/', $child->value()))->count();

            // check if it contains a power
            if ($node->children()->filter(fn ($child) => $child->value() === '^')->count() > 0) {
                return 1000;
            }

            if ($amountOfLetterChildren === 0) {
                return 20;
            }

            if ($amountOfLetterChildren === 1) {
                // when it consits of 1 letter, get the value for that letter
                return $this->getValue($node->children()->filter(fn ($child) => preg_match('/[a-z]/', $child->value()))->first());
            }

            if ($amountOfLetterChildren > 1) {
                return 30;
            }
        }

        return 1000;
    }
}
