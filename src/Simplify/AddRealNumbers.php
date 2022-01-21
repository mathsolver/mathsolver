<?php

namespace MathSolver\Simplify;

use MathSolver\Utilities\Node;

class AddRealNumbers
{
    /**
     * Add all real numbers together. For example 9 + 5 -> 14.
     */
    public function run(Node $node): Node
    {
        $node->setChildren($node->children()->map(fn ($child) => $this->run($child)));

        if ($node->value() !== '+') {
            return $node;
        }

        $total = $node->numericChildren()
            ->each(fn ($child) => $node->removeChild($child))
            ->reduce(fn ($total, $number) => $number->value() + $total, 0);

        if ($node->nonNumericChildren()->count() === 0) {
            return new Node($total);
        }

        if ($total !== 0) {
            $node->appendChild(new Node($total));
        }

        return tap($node)->setChildren($node->children()->values());
    }
}
