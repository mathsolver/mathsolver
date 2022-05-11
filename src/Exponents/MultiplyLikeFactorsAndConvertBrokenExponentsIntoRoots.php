<?php

namespace MathSolver\Exponents;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Fraction;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class MultiplyLikeFactorsAndConvertBrokenExponentsIntoRoots extends Step
{
    /**
     * Multiply like factors and convert broken exponents into roots.
     *
     * 1. Calculate the total exponent per factor
     * 2. Append each factor with their exponent to the root node
     */
    public function handle(Node $node): Node
    {
        // Wrap in times if not already so
        if ($node->value() !== '*') {
            $node = tap(new Node('*'))->appendChild($node);
        }

        // Calculate the total exponents of each factor
        $totals = $this->calculateTotals($node);

        // Append all factors
        $totals->each(function (Fraction $fraction, string $factor) use ($node) {
            $node->appendChild($this->pushFactor($factor, $fraction));
        });

        return $node;
    }

    /**
     * Run this in multiplications, or in powers (but only if it is not already in a multiplication).
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === '*'
            || ($node->value() === '^' && $node->parent()?->value() !== '*');
    }

    /**
     * Loop through each factor and add up the exponents.
     */
    protected function calculateTotals(Node $times): Collection
    {
        $totals = new Collection();

        $times->children() // Get all children
            ->filter(fn (Node $child) => !$child->isNumeric()) // Filter out numbers
            ->filter(function (Node $child) { // Filter out powers with non-number exponents
                if ($child->value() !== '^') {
                    return true;
                }
                if (is_numeric($child->child(1)->value()) && floor($child->child(1)->value()) != $child->child(1)->value()) {
                    return false;
                }
                return $child->child(1)->isNumeric();
            })
            ->each(fn (Node $child) => $times->removeChild($child)) // Remove the child from their parent
            ->map(fn (Node $child) => $this->getValueAndExponent($child)) // Get the value of the exponent
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

    /**
     * Find the value of the exponent for roots, fractions, and natural-number exponents.
     */
    protected function getValueAndExponent(Node $node): array
    {
        // It's a root
        if ($node->value() === 'root') {
            $degree = $node->child(1)->value();

            return [
                'value' => $node->child(0)->toString(),
                'exponent' => new Fraction(1, $degree),
            ];
        }

        // Power with root inside
        if ($node->value() === '^' && $node->child(0)->value() === 'root') {
            $fraction = new Fraction($node->child(1)->value(), $node->child(0)->child(1)->value());

            return [
                'value' => $node->child(0)->child(0)->toString(),
                'exponent' => $fraction,
            ];
        }

        // Exponent is 1 (no power)
        if ($node->value() !== '^') {
            return [
                'value' => $node->toString(),
                'exponent' => new Fraction(1),
            ];
        }

        // Exponent is natural number
        if ($node->child(1)->value() !== 'frac') {
            return [
                'value' => $node->child(0)->toString(),
                'exponent' => new Fraction($node->child(1)->value()),
            ];
        }

        // Exponent is fraction
        $numerator = $node->child(1)->child(0)->value();
        $denominator = $node->child(1)->child(1)->value();

        return [
            'value' => $node->child(0)->toString(),
            'exponent' => new Fraction($numerator, $denominator),
        ];
    }

    /**
     * Create the new node and then return it.
     *
     * 1. Check if the new exponent is 1
     * 2. Create a power for the whole part of the fraction
     * 3. Create a root for the fraction part
     * 4. Return the proper result
     */
    protected function pushFactor(string $factor, Fraction $fraction): Node
    {
        // Check if the exponent is 1, so no power or root is needed
        if ($fraction->numerator() === 1 && $fraction->denominator() === 1) {
            return Node::fromString($factor);
        }

        // Check if the denominator is 1, so only a power is needed
        if ($fraction->denominator() === 1) {
            $power = new Node('^');
            $power->appendChild(Node::fromString($factor));
            $power->appendChild(new Node($fraction->wholePart()));
            return $power;
        }

        // Create a power and append the root to it
        $power = new Node('^');
        $root = $power->appendChild(new Node('root'));
        $root->appendChild(Node::fromString($factor));
        $root->appendChild(new Node($fraction->denominator()));
        $power->appendChild(new Node($fraction->numerator()));

        // If the numerator is 1, only the root is needed
        if ($fraction->numerator() === 1) {
            return $root;
        }

        return $power;
    }
}
