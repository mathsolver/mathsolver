<?php

namespace MathSolver;

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;
use MathSolver\Utilities\TreeToStringConverter;

class Math
{
    /**
     * Whether to record steps.
     */
    protected bool $withSteps = false;

    /**
     * The recorded steps.
     */
    protected array $steps = [];

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
    public function string(): string|array
    {
        return $this->withSteps
            ? ['result' => TreeToStringConverter::run($this->tree), 'steps' => $this->steps]
            : TreeToStringConverter::run($this->tree);
    }

    /**
     * Return the current math tree.
     */
    public function tree(): Node
    {
        return $this->tree;
    }

    /**
     * Convert to a mathjax formatted string.
     */
    public function mathjax(): string|array
    {
        return $this->withSteps
            ? ['result' => TreeToStringConverter::run($this->tree, $mathjax = true), 'steps' => $this->steps]
            : TreeToStringConverter::run($this->tree, $mathjax = true);
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
        $result = Simplifier::run($this->tree, $this->withSteps);

        if ($this->withSteps) {
            $this->tree = $result['tree'];
            $this->steps = $result['steps'];
        } else {
            $this->tree = $result;
        }

        return $this;
    }

    /**
     * Record steps.
     */
    public function withSteps(): self
    {
        $this->withSteps = true;
        return $this;
    }
}
