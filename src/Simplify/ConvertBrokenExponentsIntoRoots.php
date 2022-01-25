<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;

class ConvertBrokenExponentsIntoRoots extends Step
{
    /**
     * Convert broken exponents into whole exponents and roots.
     *
     * For example: x^2.5 -> x^2 * root(x, 2)
     *
     * @return Collection<Node>|Node
     */
    public function handle(Node $node): Collection|Node
    {
        // Get the base and the exponent
        $base = $node->children()->first();
        $exponent = $node->children()->last()->value();

        // Create the root node
        $root = new Node('root');
        $root->appendChild(clone $base);
        $root->appendChild(new Node(pow($exponent - floor($exponent), -1)));

        // Check if the whole exponent equals 0
        if (floor($exponent) == 0) {
            return $root;
        }

        // Create a multiplication
        $times = new Node('*');

        // Add the whole exponent to the multiplication
        $power = $times->appendChild(new Node('^'));
        $power->appendChild(clone $base);
        $power->appendChild(new Node(floor($exponent)));

        // Append the root
        $times->appendChild($root);

        // Return the multiplication
        return $node->parent()?->value() === '*'
            ? $times->children()
            : $times;
    }

    /**
     * Only run this function when the exponent isn't whole.
     */
    public function shouldRun(Node $node): bool
    {
        if ($node->value() !== '^') {
            return false;
        }

        $exponent = $node->children()->last()->value();
        return is_numeric($exponent) && floor($exponent) != $exponent;
    }
}
