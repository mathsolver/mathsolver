<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;

class RemoveBracketsWithPlus extends Step
{
    /**
     * The current node to remove the brackets on.
     */
    public Node $node;

    /**
     * Remove brackets with a plus. For example 3(p + 4) -> 3p + 3*4 and (x+3)(y-5) -> xy + 5x + 3y + 3*-5.
     */
    public function handle(Node $node): Node
    {
        $node->setChildren($node->children()->map(fn ($child) => $this->run($child)));
        $this->node = $node;

        if (!$this->shouldRun()) {
            $this->removeNestedOperations();
            return $this->node;
        }

        [$children1, $children2] = $this->getChildrenToMultiply();
        $this->multiplyChildren($children1, $children2);

        if ($this->node->children()->count() === 1 && $this->node->children()->first()->value() === '(') {
            $this->node = $this->node->children()->first()->children()->first();
            $this->node->setParent(null);
        }

        return $this->run($this->node);
    }

    /**
     * Determine whether this check should be run.
     */
    protected function shouldRun(): bool
    {
        if ($this->node->value() !== '*') {
            return false;
        }

        if ($this->node->children()->count() <= 1) {
            return false;
        }

        return $this->node->children()->filter(function (Node $brackets) {
            return $brackets->value() === '(' && $brackets->children()->first()->value() === '+';
        })->count() > 0;
    }

    /**
     * Get two collections of nodes to multiply with eachother.
     *
     * @return Collection<Node>[]
     */
    protected function getChildrenToMultiply(): array
    {
        $brackets = $this->node->children()->filter(function (Node $brackets) {
            return $brackets->value() === '(' && $brackets->children()->first()->value() === '+';
        })->first();

        $this->node->removeChild($brackets);
        $bracketChildren = $brackets->children()->first()->children();

        $otherNode = $this->node->children()->filter(fn ($child) => $child !== $brackets)->first();
        $this->node->removeChild($otherNode);

        $otherChildren = $otherNode->value() === '(' && $otherNode->children()->first()->value() === '+'
            ? $otherNode->children()->first()->children()
            : collect([$otherNode]);

        return [$bracketChildren, $otherChildren];
    }

    /**
     * Append all the children as a multiplication.
     *
     * @param Collection<Node> $children1
     * @param Collection<Node> $children2
     */
    protected function multiplyChildren(Collection $children1, Collection $children2): void
    {
        $brackets = $this->node->appendChild(new Node('('));
        $plus = $brackets->appendChild(new Node('+'));

        $children1->each(function (Node $child1) use ($plus, $children2) {
            $children2->each(function (Node $child2) use ($plus, $child1) {
                $times = $plus->appendChild(new Node('*'));

                $child2->value() === '*'
                    ? $child2->children()->each(fn ($child) => $times->appendChild(clone $child))
                    : $times->appendChild(clone $child2);

                $child1->value() === '*'
                    ? $child1->children()->each(fn ($child) => $times->appendChild(clone $child))
                    : $times->appendChild(clone $child1);
            });
        });
    }

    /**
     * If a operation contains other same-operation-nodes, remove them.
     */
    protected function removeNestedOperations(): void
    {
        if (!in_array($this->node->value(), ['+', '*'])) {
            return;
        }

        $this->node
            ->children()
            ->filter(fn ($child) => $child->value() === $this->node->value())
            ->each(function ($plus) {
                $this->node->removeChild($plus);
                $plus->children()->each(fn ($child) => $this->node->appendChild($child));
                $this->node->setChildren($this->node->children()->values());
            });
    }
}
