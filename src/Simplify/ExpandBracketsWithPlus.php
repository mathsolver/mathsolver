<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class ExpandBracketsWithPlus extends Step
{
    /**
     * The current node to remove the brackets on.
     */
    public Node $node;

    /**
     * Remove brackets with a plus. For example 3(p + 4) -> 3p + 3*4 and (x+3)(y-5) -> xy + 5x + 3y + 3*-5.
     */
    public function handle(Node $node): Node|Collection
    {
        $this->node = $node;

        [$children1, $children2] = $this->getChildrenToMultiply();
        $this->multiplyChildren($children1, $children2);

        if ($this->node->children()->count() === 1 && $this->node->child(0)->value() === '(') {
            $this->node = $this->node->child(0)->child(0);
            $this->node->setParent(null);
        }

        return $this->run($this->node);
    }

    /**
     * Determine whether this function should run.
     */
    public function shouldRun(Node $node): bool
    {
        if ($node->value() !== '*') {
            return false;
        }

        return $node->children()->filter(function (Node $brackets) {
            return $brackets->value() === '(' && $brackets->child(0)->value() === '+';
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
            return $brackets->value() === '(' && $brackets->child(0)->value() === '+';
        })->first();

        $this->node->removeChild($brackets);
        $bracketChildren = $brackets->child(0)->children();

        $otherNode = $this->node->children()->filter(fn ($child) => $child !== $brackets)->first();
        $this->node->removeChild($otherNode);

        $otherChildren = $otherNode->value() === '(' && $otherNode->child(0)->value() === '+'
            ? $otherNode->child(0)->children()
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
                    ? $child2->children()->each(fn ($child) => $times->appendChild($child->clone()))
                    : $times->appendChild($child2->clone());

                $child1->value() === '*'
                    ? $child1->children()->each(fn ($child) => $times->appendChild($child->clone()))
                    : $times->appendChild($child1->clone());
            });
        });
    }
}
