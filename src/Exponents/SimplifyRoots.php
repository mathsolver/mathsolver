<?php

namespace MathSolver\Exponents;

use Illuminate\Support\Collection;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\PrimeFactorer;
use MathSolver\Utilities\Step;

class SimplifyRoots extends Step
{
    /**
     * Try to bring a factor before the root sign.
     *
     * @return Collection<Node>|Node
     */
    public function handle(Node $node): Node
    {
        if ($node->child(0)->value() == 0) {
            return new Node(0);
        }

        $degree = $node->children()->last()->value();

        $factors = $this->isNegative($node)
            ? PrimeFactorer::run($node->child(0)->value() * -1)
            : PrimeFactorer::run($node->child(0)->value());

        [$outsideRoot, $insideRoot] = $this->findResults($degree, $factors);

        return $this->compileNode($degree, $outsideRoot, $insideRoot, $node);
    }

    /**
     * Only run this function when all nodes are real numbers.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'root'
            && is_numeric($node->child(0)->value())
            && is_numeric($node->children()->last()->value())
            && floor($node->child(0)->value()) == $node->child(0)->value()
            && floor($node->children()->last()->value()) == $node->children()->last()->value()
            && ($node->child(0)->value() >= 0 || $node->children()->last()->value() % 2 === 1);
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
            return new Node($this->isNegative($node) ? $outsideRoot * -1 : $outsideRoot);
        }

        // Check if the coefficient is 1
        if ($outsideRoot === 1) {
            $root = new Node('root');
            $root->appendChild(new Node($this->isNegative($node) ? $insideRoot * -1 : $insideRoot));
            $root->appendChild(new Node($degree));
            return $root;
        }

        // Wrap in a multiplication
        $times = new Node('*');
        $times->appendChild(new Node($this->isNegative($node) ? $outsideRoot * -1 : $outsideRoot));
        $root = $times->appendChild(new Node('root'));
        $root->appendChild(new Node($insideRoot));
        $root->appendChild(new Node($degree));

        return $times;
    }

    /**
     * Determine whether the number inside a root is negative or not.
     */
    protected function isNegative(Node $node): bool
    {
        return $node->child(0)->value() < 0;
    }
}
