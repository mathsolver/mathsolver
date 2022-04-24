<?php

namespace MathSolver\Derivatives;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class PowerRule extends Step
{
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'deriv'
            && $node->child(0)->value() === '^'
            && $node->child(0)->child(0)->value() === 'x';
    }

    public function handle(Node $node): Node
    {
        $exponent = $node->child()->child(1)->value();

        $times = new Node('*');
        $times->appendChild(new Node($exponent));
        $times->appendChild($node->child());

        if (is_numeric($exponent)) {
            $newExponent = new Node($exponent - 1);
        } else {
            $newExponent = new Node('+');
            $newExponent->appendChild(new Node($exponent));
            $newExponent->appendChild(new Node(-1));
        }

        $node->child()->removeChild($node->child()->child(-1));
        $node->child()->appendChild($newExponent);

        return $times;
    }
}