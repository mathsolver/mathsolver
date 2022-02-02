<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;

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

        if ($this->node->children()->count() === 1) {
            return tap($this->node->children()->first())->setParent(null);
        }

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

        $childrenWithChildrenWithMultipleNumberProducts = $node->children()->filter(fn ($child) => $child->numericChildren()->count() < 2);
        return $childrenWithChildrenWithMultipleNumberProducts->count() === $node->children()->count();
    }

    /**
     * Add all same nodes together and total them up.
     */
    protected function calculateTotals(): void
    {
        $this->node->children()
            ->map(fn ($child) => $this->wrapInMultiplication($child))
            ->filter(fn ($child) => $child->value() === '*')
            ->each(function (Node $times) {
                $coefficient = $times->numericChildren()->first()?->value() ?? 1;
                $terms = $times->nonNumericChildren()->map(fn (Node $child) => $child->toString()); /** @var Collection<string> $terms */
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
    protected function pushToTotals(float $coefficient, Collection $terms): void
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
            'coefficient' => $total['coefficient'] + $coefficient,
            'terms' => $terms,
        ]);
    }

    /**
     * Append a new child to the parent node.
     *
     * @param Collection<string> $terms
     */
    protected function appendChildToNode(float $coefficient, Collection $terms): void
    {
        if ($coefficient == 0) {
            return;
        }

        $node = new Node('*');

        if ($coefficient != 1) {
            $node->appendChild(new Node($coefficient));
        }

        $terms->map(fn ($term) => Node::fromString($term))
            ->each(fn ($term) => $node->appendChild($term));

        if ($node->children()->count() === 1) {
            $this->node->appendChild(tap($node->children()->first())->setParent(null));
            return;
        }

        $this->node->appendChild($node);
    }
}
