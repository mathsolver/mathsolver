<?php

namespace MathSolver;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\TreeToStringConverter;

class MathObject
{
    public Node $tree;

    public function __construct(Node $tree)
    {
        $this->tree = $tree;
    }

    public function __toString(): string
    {
        return $this->string();
    }

    public function string(): string
    {
        return TreeToStringConverter::run($this->tree);
    }

    public function simplify(): self
    {
        $this->tree = Runner::run($this->tree);

        return $this;
    }
}
