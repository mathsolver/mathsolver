<?php

namespace MathSolver\Derivative;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class SumRule extends Step
{
    /**
     * Only run in additions.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'deriv'
            && $node->child(0)->value() === '+';
    }

    /**
     * Split the deriv functions.
     */
    public function handle(Node $deriv): Node|Collection
    {
        // create a new plus node
        $plus = new Node('+');

        $deriv->child(0) // get the plus node
            ->children() // loop over all children
            ->map(fn (Node $child) => tap(new Node('deriv'))->appendChild($child)) // create a new deriv function
            ->each(fn (Node $deriv) => $plus->appendChild($deriv)); // append the deriv function to the plus node

        // determine whether to return the children or the plus node itsself
        return $deriv->parent()?->value() === '+' ? $plus->children() : $plus;
    }
}
