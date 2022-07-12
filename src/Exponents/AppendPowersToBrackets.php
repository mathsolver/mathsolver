<?php

namespace MathSolver\Exponents;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AppendPowersToBrackets extends Step
{
    /**
     * Append powers to multiplication inside brackets.
     *
     * For example: (xy)^2 => x^2 * y^2
     */
    public function handle(Node $power): Node
    {
        // Find the exponent
        $exponent = $power->child(1);

        // Create a new times node
        $times = new Node('*');

        // Loop over all factors inside the brackets
        $power->child(0)->child(0)
            ->children()
            ->each(function (Node $child) use ($times, $exponent) {
                $power = $times->appendChild(new Node('^'));

                // Add brackets around the factor if it is a power
                if ($child->value() === '^') {
                    $brackets = $power->appendChild(new Node('('));
                    $brackets->appendChild($child);
                } else {
                    $power->appendChild($child->wrapInBrackets('^'));
                }

                $power->appendChild(clone $exponent);
            });

        // Return the times node
        return $times;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '^'
            && $node->child(0)->value() === '('
            && $node->child(0)->child(0)->value() === '*';
    }
}
