<?php

namespace MathSolver\Derivative;

use Illuminate\Support\Collection;
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
            ->filter(fn ($child) => !$child->contains($this->respect($deriv)))
            ->each(fn ($child) => $times->appendChild($child, $top = true))
            ->each(fn ($child) => $deriv->child(0)->removeChild($child, $resetIndexes = false));

        // remove the times inside the derivative if it has only one child left
        if ($deriv->child(0)->children()->count() === 1) {
            $deriv->appendChild($deriv->child(0)->children()->first(), $top = true);
            $deriv->removeChild($deriv->child(1));
        }

        $deriv->child(0)->setChildren($deriv->child(0)->children()->values());

        // return the deriv function if no constants were found
        if ($times->children()->count() === 1) {
            return tap($times->child(0))->setParent(null);
        }

        // determine whether to return the children or the times node itsself
        return $timesAlreadyExists ? $times->children() : $times;
    }
}
