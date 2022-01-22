<?php

namespace MathSolver;

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;
use MathSolver\Utilities\TreeToStringConverter;

class Math
{
    /**
     * The root node of the current math tree.
     */
    public Node $tree;

    /**
     * Convert the given string to a tree.
     */
    public function __construct(string $expression)
    {
        $this->tree = StringToTreeConverter::run($expression);
    }

    /**
     * Convert the current math tree to a string.
     */
    public function __toString(): string
    {
        return TreeToStringConverter::run($this->tree);
    }

    /**
     * Parse a mathematical expression to a math tree.
     */
    public static function from(string $expression): self
    {
        return new self($expression);
    }

    /**
     * Simplify the expression as much as possible.
     */
    public function simplify(): self
    {
        $this->tree = Simplifier::run($this->tree);
        return $this;
    }
}
