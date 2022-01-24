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
    protected Node $tree;

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
        return $this->string();
    }

    /**
     * Convert the current math tree to a string.
     */
    public function string(): string
    {
        return TreeToStringConverter::run($this->tree);
    }

    /**
     * Return the current math tree.
     */
    public function tree(): Node
    {
        return $this->tree;
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
