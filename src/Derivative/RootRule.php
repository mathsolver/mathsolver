<?php

namespace MathSolver\Derivative;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class RootRule extends Step
{
    use DifferentiateWithRespect;

    /**
     * Differentiate roots using the root rule.
     *
     * Root rule: d/dx(root[x, n]) => frac[1, n] * root[x, n]^(-n + 1).
     */
    public function handle(Node $deriv): Node
    {
        // Find important nodes
        $degree = $deriv->child(0)->child(1);
        $function = $deriv->child(0)->child(0);

        // Create a new times node
        $times = new Node('*');

        // Append the fraction to the times node
        if (is_numeric($degree->value())) {
            $fraction = $times->appendChild(new Node('frac'));
            $fraction->appendChild(new Node(1));
            $fraction->appendChild(clone $degree);
        } else {
            $power = $times->appendChild(new Node('^'));
            $power->appendChild($degree);
            $power->appendChild(new Node(-1));
        }

        // Append the power to the times node
        $power = $times->appendChild(new Node('^'));

        // Append the root to the power node
        $root = $power->appendChild(new Node('root'));
        $root->appendChild(clone $function);
        $root->appendChild(clone $degree);

        // Append the exponent to the power node
        if (is_numeric($degree->value())) {
            $power->appendChild(new Node(1 - $degree->value()));
        } else {
            $brackets = $power->appendChild(new Node('('));
            $plus = $brackets->appendChild(new Node('+'));
            $nestedTimes = $plus->appendChild(new Node('*'));
            $nestedTimes->appendChild(new Node(-1));
            $nestedTimes->appendChild(clone $degree);
            $plus->appendChild(new Node(1));
        }

        // Return the times node
        return $times;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'deriv'
            && $node->child(0)->value() === 'root'
            && $node->child(0)->child(0)->value() === $this->respect($node)
            && !$node->child(0)->child(1)->contains($this->respect($node));
    }
}
