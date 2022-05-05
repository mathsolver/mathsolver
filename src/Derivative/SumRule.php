<?php

namespace MathSolver\Derivative;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class SumRule extends Step
{
    use DifferentiateWithRespect;

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
    public function handle(Node $deriv): Node
    {
        // Create a new plus node
        $plus = new Node('+');

        $deriv->child(0) // Get the plus node
            ->children() // Loop over all children
            ->map(function (Node $child) use ($deriv) { // Create a new deriv function
                $newDeriv = new Node('deriv');
                $newDeriv->appendChild($child);

                if ($this->respect($deriv) !== 'x') {
                    $newDeriv->appendChild(new Node($this->respect($deriv)));
                }

                return $newDeriv;
            })
            ->each(fn (Node $deriv) => $plus->appendChild($deriv)); // Append the deriv function to the plus node

        // Determine whether to return the children or the plus node itsself
        return $plus;
    }
}
