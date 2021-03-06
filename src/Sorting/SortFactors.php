<?php

namespace MathSolver\Sorting;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

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
        // Check if it is a number
        if ($node->isNumeric()) {
            return 1000;
        }

        // Check if it is a letter
        if (preg_match('/[a-z]/', $node->value())) {
            return ord($node->value()) * -1 + 200;
        }

        if ($node->value() === '^' && ctype_alpha((string) $node->child(0)->value())) {
            return ord($node->child(0)->value()) * -1 + 200;
        }

        // Default value
        return 0;
    }
}
