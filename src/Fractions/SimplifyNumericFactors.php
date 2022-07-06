<?php

namespace MathSolver\Fractions;

use MathSolver\Utilities\Fraction;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class SimplifyNumericFactors extends Step
{
    /**
     * Reduce fractions such as 2x/4 to x/2.
     */
    public function handle(Node $node): Node
    {
        $numeratorNumber = $node->child(0)->numericChildren()->first() ?? $node->child(0);
        $denominatorNumber = $node->child(1)->numericChildren()->first() ?? $node->child(1);

        if ((float) $numeratorNumber->value() !== floor($numeratorNumber->value()) || (float) $denominatorNumber !== floor($denominatorNumber->value())) {
            return $node;
        }

        $fraction = Fraction::fromFloat($numeratorNumber->value(), $denominatorNumber->value())->simplify();
        $newNumerator = $fraction->numerator();
        $newDenominator = $fraction->denominator();

        $numeratorNumber->setValue($newNumerator);
        $denominatorNumber->setValue($newDenominator);

        // Denominator is 1
        if ($node->child(1)->value() == 1) {
            return $node->child(0);
        }

        return $node;
    }

    /**
     * Run this step when it is a fraction with multiplications or numbers inside.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && (($node->child(0)->value() === '*' && $node->child(0)->numericChildren()->count() > 0) || is_numeric($node->child(0)->value()))
            && (($node->child(1)->value() === '*' && $node->child(1)->numericChildren()->count() > 0) || is_numeric($node->child(1)->value()))
            && !(is_numeric($node->child(0)->value()) && is_numeric($node->child(1)->value()));
    }
}
