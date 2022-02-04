<?php

namespace MathSolver;

use Illuminate\Support\Collection;
use MathSolver\Simplify\Simplifier;
use MathSolver\Solve\Solver;
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
        'withSteps' => false,
    ];

    /**
     * The recorded steps.
     *
     * @var Collection<array>
     */
    public Collection $steps;

    /**
     * Create a new Math instance.
     *
     * @return Math
     */
    public function __construct(string $expression)
    {
        $this->tree = StringToTreeConverter::run($expression);
        $this->steps = new Collection();
    }

    /**
     * Convert the expression to a string.
     */
    public function __toString(): string
    {
        return $this->string();
    }

    /**
     * Convert the expression to a string.
     */
    public function string(): string|array
    {
        $result = TreeToStringConverter::run($this->tree, $this->options['mathjax']);

        return $this->options['withSteps']
            ? ['result' => $result, 'steps' => $this->steps->toArray()]
            : $result;
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
     * - `withSteps (bool)` whether to record steps
     */
    public function config(array $options): self
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }

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
        $result = Simplifier::run($this->tree, $this->options['mathjax']);

        $this->tree = $result['tree'];

        $this->steps = collect($result['steps']);

        return $this;
    }

    /**
     * Solve an equation for a letter.
     */
    public function solveFor(string $letter): self
    {
        $this->tree = Solver::run($this->tree, $letter);
        return $this;
    }
}
