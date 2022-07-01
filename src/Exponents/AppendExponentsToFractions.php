<?php

namespace MathSolver\Exponents;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AppendExponentsToFractions extends Step
{
    /**
     * Calculate the logarithm of a number.
     */
    public function handle(Node $power): Node
    {
        $fraction = $power->child(0);
        $exponent = $power->child(1);

        $numerator = new Node('^');
        $numerator->appendChild($fraction->child(0)->clone()->wrapInBrackets('^'));
        $numerator->appendChild($exponent->clone());

        $denominator = new Node('^');
        $denominator->appendChild($fraction->child(1)->clone()->wrapInBrackets('^'));
        $denominator->appendChild($exponent->clone());

        $fraction->replaceChild($fraction->child(0), $numerator);
        $fraction->replaceChild($fraction->child(1), $denominator);

        return $fraction;
    }

    /**
     * Determine whether this function should run.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '^' && $node->child(0)->value() === 'frac';
    }
}
