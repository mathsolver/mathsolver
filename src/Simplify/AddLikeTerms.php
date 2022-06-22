<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Fraction;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class AddLikeTerms extends Step
{
    /**
     * The key-value pairs of totals. For example: ['x' => 6, 'xy' => 9, 'y' => 4].
     *
     * @var Collection<string,integer> $totals
     */
    protected Collection $totals;

    /**
     * The current parent node.
     */
    protected Node $node;

    /**
     * Combine like terms in an addition. For example 4x + 5x -> 9x and 3y^3 + 6y^3 -> 9y^3.
     */
    public function handle(Node $node): Node
    {
        $this->totals = new Collection();

        $this->node = $node;

        $this->calculateTotals();

        $this->totals->each(function ($total) {
            $this->appendChildToNode($total['coefficient'], $total['terms']);
        });

        return $this->node;
    }

    /**
     * Determine whether the function should run.
     */
    public function shouldRun(Node $node): bool
    {
        if ($node->value() !== '+') {
            return false;
        }

        $termsWithOneNumericFactor = $node
            ->children()
            ->filter(
                fn (Node $term) => $term->value() !== '*' || $term
                    ->children()
                    ->filter(fn (Node $factor) => $factor->isNumeric())
                    ->count() < 2
            )
            ->count();

        return $termsWithOneNumericFactor == $node->children()->count();
    }

    /**
     * Add all same nodes together and total them up.
     */
    protected function calculateTotals(): void
    {
        $this->node->children()
            // Fractions can only contain integers
            ->filter(function (Node $node) {
                if ($node->value() === 'frac' && $node->isNumeric()) {
                    return (float) $node->child(0)->value() === floor($node->child(0)->value())
                        && (float) $node->child(1)->value() === floor($node->child(1)->value());
                }

                return true;
            })
            ->map(fn ($child) => $this->wrapInMultiplication($child))
            ->filter(fn ($child) => $child->value() === '*')
            ->each(function (Node $times) {
                if ($times->numericChildren(false)->first()?->value() === 'frac') {
                    $fraction = $times->numericChildren(false)->first();
                    $coefficient = new Fraction($fraction->child(0)->value(), $fraction->child(1)->value());
                } else {
                    $coefficient = Fraction::fromFloat($times->numericChildren(false)->first()?->value() ?? 1);
                }

                $terms = $times->nonNumericChildren(false)->map(fn (Node $child) => $child->toString()); /** @var Collection<string> $terms */
                $this->pushToTotals($coefficient, $terms);
            })
            ->each(fn ($times) => $this->node->removeChild($times));
    }

    /**
     * Wrap the node in a multiplication if it is a letter or a power.
     */
    protected function wrapInMultiplication(Node $node): Node
    {
        if (preg_match('/[a-z]/', $node->value())) {
            $this->node->removeChild($node);
            return tap(new Node('*'))->appendChild($node);
        }

        if ($node->value() === '^' && is_numeric($node->children()->last()->value())) {
            $this->node->removeChild($node);
            return tap(new Node('*'))->appendChild($node);
        }

        return $node;
    }

    /**
     * Add a new item to the $totals array.
     *
     * @param Collection<string> $terms
     */
    protected function pushToTotals(Fraction $coefficient, Collection $terms): void
    {
        $total = $this->totals->first(fn ($total) => $total['terms'] == $terms);

        if (!$total) {
            $this->totals->push([
                'coefficient' => $coefficient,
                'terms' => $terms,
            ]);

            return;
        }

        $index = $this->totals->search($total);

        $this->totals->put($index, [
            'coefficient' => $total['coefficient']->add($coefficient->numerator(), $coefficient->denominator()),
            'terms' => $terms,
        ]);
    }

    /**
     * Append a new child to the parent node.
     *
     * @param Collection<string> $terms
     */
    protected function appendChildToNode(Fraction $coefficient, Collection $terms): void
    {
        if ($coefficient->numerator() === 0) {
            return;
        }

        $node = new Node('*');

        // Only append the coefficient if it is not 1
        if ($coefficient->numerator() !== 1 || $coefficient->denominator() !== 1) {
            $node->appendChild($coefficient->simplify()->node());
        }

        $terms->map(fn ($term) => Node::fromString($term))
            ->each(fn ($term) => $node->appendChild($term));

        if ($node->children()->count() === 1) {
            $this->node->appendChild(tap($node->child(0))->setParent(null));
            return;
        }

        $this->node->appendChild($node);
    }
}
