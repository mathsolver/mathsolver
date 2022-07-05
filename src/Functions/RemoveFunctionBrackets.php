<?php

namespace MathSolver\Functions;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;
use MathSolver\Utilities\StringToTreeConverter;

class RemoveFunctionBrackets extends Step
{
    /**
     * Remove function brackets in the math tree.
     */
    public function handle(Node $node): Node
    {
        // Move all children of the brackets node to the function node
        $node->child(0)->children()->each(function ($child) use ($node) {
            $node->appendChild($child);
        });

        // Remove the brackets node
        $node->removeChild($node->child(0));

        return $node;
    }

    /**
     * Only run for functions.
     */
    public function shouldRun(Node $node): bool
    {
        return in_array($node->value(), StringToTreeConverter::$functions);
    }
}
