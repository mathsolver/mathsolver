<?php

namespace MathSolver\Exponents;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class ConvertFractionsIntoNegativeExponents extends Step
{
    /**
     * Replace all powers with an exponent of zero by a one.
     *
     * For example: 6^0 -> 1, x^0 -> 1.
     */
    public function handle(Node $frac): Node
    {
        $times = new Node('*');

        if ($frac->child(0)->value() != 1) {
            $times->appendChild($frac->child(0));
        }

        $power = $times->appendChild(new Node('^'));

        if ($frac->child(1)->value() === '^' && is_numeric($frac->child(1)->child(1)->value())) {
            $power->appendChild($frac->child(1)->child(0));
            $power->appendChild(new Node(-1 * $frac->child(1)->child(1)->value()));
        } elseif ($frac->child(1)->value() === '^') {
            $brackets = $power->appendChild(new Node('('));
            $brackets->appendChild($frac->child(1));
            $power->appendChild(new Node(-1));
        } else {
            $power->appendChild($frac->child(1));
            $power->appendChild(new Node(-1));
        }

        return $times;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && !$node->child(1)->isNumeric();
    }
}
