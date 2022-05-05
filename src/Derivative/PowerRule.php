<?php

namespace MathSolver\Derivative;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class PowerRule extends Step
{
    use DifferentiateWithRespect;

    /**
     * Check if it is a power.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'deriv'
            && $node->child(0)->value() === '^'
            && $node->child(0)->child(0)->value() === $this->respect($node);
    }

    /**
     * Multiply by the exponent and subtract one of the exponent.
     *
     * For example: `x^5 -> 5x^4`.
     */
    public function handle(Node $deriv): Node
    {
        // Find the old exponent
        $exponent = $deriv->child()->child(1)->value();

        // Multiply the old exponent by the power
        $times = new Node('*');
        $times->appendChild(new Node($exponent));
        $times->appendChild($deriv->child(0));

        // Create the new exponent
        if (is_numeric($exponent)) {
            $newExponent = new Node($exponent - 1);
        } else {
            $newExponent = new Node('(');
            $plusInExponent = $newExponent->appendChild(new Node('+'));
            $plusInExponent->appendChild(new Node($exponent));
            $plusInExponent->appendChild(new Node(-1));
        }

        // Append the new exponent to the power
        $deriv->child()->removeChild($deriv->child()->child(-1));
        $deriv->child()->appendChild($newExponent);

        return $times;
    }
}
