<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;
use MathSolver\Utilities\StringToTreeConverter;

class RemoveBrackets extends Step
{
    /**
     * Remove brackets when the outside presedence is lower than the inside.
     */
    public function handle(Node $node): Node|Collection
    {
        // there isn't anything outside the brackets
        if (is_null($node->parent())) {
            return tap($node->child(0))->setParent(null);
        }

        // inside brackets is higher than outside
        if (StringToTreeConverter::getPrecedence($node->child(0)->value()) > StringToTreeConverter::getPrecedence($node->parent()->value())) {
            $node->parent()->removeChild($node);
            return $node->child(0);
        }

        // inside brackets is equal to outside, only with + and *
        $nestedChildren = $node->child(0)->children();
        $node->parent()->removeChild($node);
        return $nestedChildren;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        // check if it are brackets
        if ($node->value() !== '(') {
            return false;
        }

        // when it is not in a power, run it
        if ($node->parent()?->value() !== '^') {
            return true;
        }

        if (ctype_alpha($node->child(0)->value()) && ctype_alpha($node->child(-1)->value())) {
            return true;
        }

        return is_numeric($node->parent()->child(-1)->value())
            && ($node->parent()->child(-1)->value() % 2 === 1
            || $node->parent()->child(-1)->value() < 0);
    }
}
