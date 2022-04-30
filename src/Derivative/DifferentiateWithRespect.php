<?php

namespace MathSolver\Derivative;

use MathSolver\Utilities\Node;

trait DifferentiateWithRespect
{
    /**
     * Find the variable to differentiate against.
     */
    protected function respect(Node $deriv): string
    {
        return $deriv->child(1)?->value() ?? 'x';
    }
}
