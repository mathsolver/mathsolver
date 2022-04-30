<?php

namespace MathSolver\Derivative;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class CoefficientRule extends Step
{
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
     * For example: `deriv(2x) -> 2*deriv(x)`.
     */
    public function handle(Node $deriv): Node|Collection
    {
        // determine if the deriv's parent is a times node
        $timesAlreadyExists = $deriv->parent()?->value() === '*';

        // create a new times node
        $times = new Node('*');
        $times->appendChild($deriv);

        // find all constant factors and append them to the $times node
        $deriv->child(0)
            ->children()
            ->filter(fn ($child) => !$child->contains('x'))
            ->each(fn ($child) => $times->appendChild($child, $top = true))
            ->each(fn ($child) => $deriv->child(0)->removeChild($child));

        // remove the times inside the derivative if it has only one child left
        if ($deriv->child(0)->children()->count() === 1) {
            $deriv->appendChild($deriv->child(0)->children()->first());
            $deriv->removeChild($deriv->child(0));
            $deriv->resetChildren();
        }

        // reset the indexes of the children of $deriv
        $deriv->child(0)->resetChildren();

        // determine whether to return the children or the times node itsself
        return $timesAlreadyExists ? $times->children() : $times;
    }
}
