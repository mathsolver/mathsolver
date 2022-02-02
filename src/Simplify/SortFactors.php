<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class SortFactors extends Step
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
        return $node->value() === '*';
    }

    /**
     * Get the value of the current node.
     */
    protected function getValue(Node $node): int
    {
        // check if it is a number
        if (is_numeric($node->value())) {
            return 1000;
        }

        // check if it is a letter
        if (preg_match('/[a-z]/', $node->value())) {
            return ord($node->value()) * -1 + 200;
        }

        if ($node->value() === '^' && ctype_alpha($node->children()->first()->value())) {
            return ord($node->children()->first()->value()) * -1 + 200;
        }

        // default value
        return 0;
    }
}
