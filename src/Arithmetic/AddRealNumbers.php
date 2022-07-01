<?php

namespace MathSolver\Arithmetic;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AddRealNumbers extends Step
{
    public static ?string $docs = <<<'MARKDOWN'
        You can add all numbers up:

        $$ 5 + 6 + 3 = 14 $$

        You can do the same with minus:

        $$ 19 - 3 + 4 = 20 $$
        MARKDOWN;

    /**
     * Add all real numbers together. For example 9 + 5 -> 14.
     */
    public function handle(Node $node): Node
    {
        $total = 0;

        foreach ($node->numericChildren() as $child) {
            $node->removeChild($child);

            $total += $child->value();
        }

        $node->appendChild(new Node($total));

        return $node;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '+' && $node->numericChildren()->count() > 1;
    }
}
