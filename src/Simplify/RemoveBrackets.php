<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;

class RemoveBrackets extends Step
{
    /**
     * Remove brackets when the outside presedence is lower than the inside.
     */
    public function handle(Node $node): Node|Collection
    {
        // inside brackets is higher than outside
        if (StringToTreeConverter::getPrecedence($node->children()->first()->value()) > StringToTreeConverter::getPrecedence($node->parent()->value())) {
            $node->parent()->removeChild($node);
            return $node->children()->first();
        }

        // inside brackets is equal to outside, only with + and *
        if (StringToTreeConverter::getPrecedence($node->children()->first()->value()) === StringToTreeConverter::getPrecedence($node->parent()->value()) && in_array($node->parent()->value(), ['+', '*'])) {
            $nestedChildren = $node->children()->first()->children();
            $node->parent()->removeChild($node);
            return $nestedChildren;
        }

        return $node;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '(';
    }
}
