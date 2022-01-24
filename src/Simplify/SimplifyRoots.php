<?php

namespace MathSolver\Simplify;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\PrimeFactorer;

class SimplifyRoots extends Step
{
    /**
     * Try to bring a factor before the root sign.
     *
     * @return Collection<Node>|Node
     */
    public function handle(Node $node): Collection|Node
    {
        $degree = $node->children()->last()->value();
        $factors = PrimeFactorer::run($node->children()->first()->value());

        [$outsideRoot, $insideRoot] = $this->findResults($degree, $factors);

        return $this->compileNode($degree, $outsideRoot, $insideRoot, $node);
    }

    /**
     * Only run this function when all nodes are real numbers.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'root' && $node->children()->count() === $node->numericChildren()->count();
    }

    /**
     * Find the number outside and inside the root sign.
     *
     * @param array<int,int> $factors
     *
     * @return array<int>
     */
    protected function findResults(int $degree, array $factors): array
    {
        $outsideRoot = 1;
        $insideRoot = 1;

        foreach (array_count_values($factors) as $factor => $count) {
            if (floor($count / $degree) > 0) {
                $outsideRoot = $outsideRoot * pow($factor, floor($count / $degree));
            }
            if ($count % $degree !== 0) {
                $insideRoot = $insideRoot * pow($factor, $count % $degree);
            }
        }

        return [$outsideRoot, $insideRoot];
    }

    /**
     * Based on the numbers inside and outside the root sign, compile a node to return.
     *
     * @return Collection<Node>|Node
     */
    protected function compileNode(int $degree, int $outsideRoot, int $insideRoot, Node $node): Collection|Node
    {
        // Check if there is no number inside the root sign
        if ($insideRoot === 1) {
            return new Node($outsideRoot);
        }

        // Check if the coefficient is 1
        if ($outsideRoot === 1) {
            $root = new Node('root');
            $root->appendChild(new Node($insideRoot));
            $root->appendChild(new Node($degree));
            return $root;
        }

        // Check if the node's parent is already a multiplication
        if ($node->parent()?->value() === '*') {
            $root = new Node('root');
            $root->appendChild(new Node($insideRoot));
            $root->appendChild(new Node($degree));

            return collect([new Node($outsideRoot), $root]);
        }

        // Wrap in a multiplication
        $times = new Node('*');
        $times->appendChild(new Node($outsideRoot));
        $root = $times->appendChild(new Node('root'));
        $root->appendChild(new Node($insideRoot));
        $root->appendChild(new Node($degree));

        return $times;
    }
}
