<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;

class MultiplyLikeTerms
{
    /**
     * Replace all double letters with a power.
     *
     * For example "x * x * x" -> "x^3".
     */
    public function run(Node $parentNode): Node
    {
        $parentNode->setChildren($parentNode->children()->map(fn ($child) => $this->run($child)));

        if ($parentNode->value() !== '*') {
            return $parentNode;
        }

        $totals = $this->calculateTotals($parentNode);

        $parentNode->removeAllChildren();

        $parentNode = $this->appendCalculatedTotals($parentNode, $totals);

        return $this->getReturnValue($parentNode);
    }

    /**
     * Calculate the totals of each term and return that collection.
     */
    protected function calculateTotals(Node $parentNode): Collection
    {
        $totals = new Collection();

        $parentNode->children()->each(function (Node $node) use ($totals) {
            if ($node->value() === '^') {
                $string = $node->children()->first()->toString();
                $increment = $node->children()->last()->value();
            } else {
                $string = $node->toString();
                $increment = 1;
            }

            if ($totals->has($string)) {
                $totals->put($string, $totals->get($string) + $increment);
            } else {
                $totals->put($string, $increment);
            }
        });

        return $totals->sortKeys(SORT_NATURAL);
    }

    /**
     * Loop over the calculated totals and append them to the parent node.
     */
    protected function appendCalculatedTotals(Node $parentNode, Collection $totals): Node
    {
        $totals->each(function (int $count, string $node) use ($parentNode) {
            if ($count === 1) {
                $parentNode->appendChild(Node::fromString($node));
                return;
            }

            $power = new Node('^');
            $power->appendChild(Node::fromString($node));
            $power->appendChild(new Node($count));
            $parentNode->appendChild($power);
        });

        return $parentNode;
    }

    /**
     * When the multiplication has only one child left (the power), return just that one child.
     */
    protected function getReturnValue(Node $node): Node
    {
        if ($node->children()->count() === 1) {
            return tap($node->children()->first())->setParent(null);
        }

        return $node;
    }
}