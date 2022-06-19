<?php

namespace MathSolver\Derivative;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class CoefficientRule extends Step
{
    use DifferentiateWithRespect;

    /**
     * Only run in multiplications.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'deriv'
            && $node->child(0)->value() === '*';
    }

    /**
     * Find all constants and bring them outside the deriv function.
     *
     * For example: `deriv[2x] -> 2*deriv[x]`.
     */
    public function handle(Node $deriv): Node
    {
        // Create a new times node
        $times = new Node('*');
        $times->appendChild($deriv);

        // Find all constant factors and append them to the $times node
        $deriv->child(0)
            ->children()
            ->filter(fn ($child) => !$child->contains($this->respect($deriv)))
            ->each(fn ($child) => $times->appendChild($child, $top = true))
            ->each(fn ($child) => $deriv->child(0)->removeChild($child, $resetIndexes = false));

        // Remove the times inside the derivative if it has only one child left
        if ($deriv->child(0)->children()->count() === 1) {
            $deriv->appendChild($deriv->child(0)->children()->first(), $top = true);
            $deriv->removeChild($deriv->child(1));
        }

        // Reset the children in the times inside the deriv
        $deriv->child(0)->setChildren($deriv->child(0)->children()->values());
        return $times;
    }
}
