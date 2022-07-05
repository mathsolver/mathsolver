<?php

namespace MathSolver\Exponents;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class ConvertRootSymbols extends Step
{
    /**
     * Convert sqrt[x] to root[x, 2] and cbrt[y] to root[y, 3].
     */
    public function handle(Node $node): Node
    {
        $root = new Node('root');

        $root->appendChild($node->child(0));

        $root->appendChild(new Node($node->value() === 'sqrt' ? 2 : 3));

        return $root;
    }

    /**
     * Only run when the value is sqrt or cbrt.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'sqrt' || $node->value() === 'cbrt';
    }
}
