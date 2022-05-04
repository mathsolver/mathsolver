<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Fraction;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MultiplyLikeFactors extends Step
{
    public function handle(Node $node): Node
    {
        $totals = $this->calculateTotals($node);

        // append all factors
        $totals->each(function (Fraction $fraction, string $factor) use ($node) {
            $node->appendChild($this->pushFactor($factor, $fraction));
        });

        // return the first child if it only has one child
        if ($node->children()->count() === 1) {
            return tap($node->child(0))->setParent(null);
        }

        return $node;
    }

    public function shouldRun(Node $node): bool
    {
        return $node->value() === '*';
    }

    protected function calculateTotals(Node $node): Collection
    {
        $totals = new Collection();

        $node->children()
            ->filter(fn (Node $child) => !$child->isNumeric())
            ->each(fn (Node $child) => $node->removeChild($child))
            ->map(fn (Node $child) => $this->getValueAndExponent($child))
            ->each(function (array $valueAndExponent) use ($totals) {
                if (!$totals->has($valueAndExponent['value'])) {
                    $totals->put($valueAndExponent['value'], $valueAndExponent['exponent']);
                    return;
                }

                $totals->put($valueAndExponent['value'], $totals[$valueAndExponent['value']]
                    ->add($valueAndExponent['exponent']->numerator(), $valueAndExponent['exponent']->denominator()));
            });

        return $totals;
    }

    protected function getValueAndExponent(Node $node)
    {
        // it's a root
        if ($node->value() === 'root') {
            $degree = $node->child(1)->value();

            return [
                'value' => $node->child(0)->toString(),
                'exponent' => new Fraction(1, $degree),
            ];
        }

        // exponent is 1 (no power)
        if ($node->value() !== '^') {
            return [
                'value' => $node->toString(),
                'exponent' => new Fraction(1),
            ];
        }

        // exponent is natural number
        if ($node->child(1)->value() !== 'frac') {
            return [
                'value' => $node->child(0)->toString(),
                'exponent' => new Fraction($node->child(1)->value()),
            ];
        }

        // exponent is fraction
        $numerator = $node->child(1)->child(0)->value();
        $denominator = $node->child(1)->child(1)->value();

        return [
            'value' => $node->child(0)->toString(),
            'exponent' => new Fraction($numerator, $denominator),
        ];
    }

    protected function pushFactor(string $factor, Fraction $fraction)
    {
        if ($fraction->simplify()->numerator() === 1 && $fraction->simplify()->denominator() === 1) {
            return Node::fromString($factor);
        }

        $power = new Node('^');

        // add brackets if the exponent is even
        if (is_numeric($factor) && $factor < 0 && is_int($fraction->simplify()->node()->value()) && $fraction->simplify()->node()->value() % 2 === 0) {
            $brackets = new Node('(');
            $brackets->appendChild(Node::fromString($factor));
            $power->appendChild($brackets);
        } else {
            $power->appendChild(Node::fromString($factor));
        }

        $power->appendChild($fraction->simplify()->node());
        return $power;
    }
}
