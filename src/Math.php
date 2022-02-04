<?php

namespace MathSolver;

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;
use MathSolver\Utilities\Substitutor;
use MathSolver\Utilities\TreeToStringConverter;

class Math
{
    /**
     * The current math tree to work with.
     */
    public Node $tree;

    /**
     * The configuration options.
     */
    public array $options = [
        'mathjax' => false,
    ];

    /**
     * Create a new Math instance.
     *
     * @return Math
     */
    public function __construct(string $expression)
    {
        $this->tree = StringToTreeConverter::run($expression);
    }

    /**
     * Convert the expression to a string.
     */
    public function __toString(): string
    {
        return $this->string();
    }

    /**
     * Initialize a Math instance from an expression.
     */
    public static function from(string $expression): self
    {
        return new self($expression);
    }

    /**
     * Specify the options for further operations.
     *
     * Available options:
     * - `mathjax (bool)` whether to use mathjax output
     */
    public function config(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Substitute values.
     *
     * For example `['x' => 5, 'y' => '3z']`.
     */
    public function substitute(array $replacements): self
    {
        $this->tree = Substitutor::run($this->tree, $replacements);
        return $this;
    }

    /**
     * Simplify the expression.
     */
    public function simplify(): self
    {
        $this->tree = Simplifier::run($this->tree)['tree'];
        return $this;
    }

    /**
     * Convert the expression to a string.
     */
    public function string(): string
    {
        return TreeToStringConverter::run($this->tree, $this->options['mathjax']);
    }
}
