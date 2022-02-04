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
     * Whether to record steps.
     */
    protected bool $withSteps = false;

    /**
     * The recorded steps.
     */
    protected Collection $steps;

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
        $this->steps = new Collection();
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
            ? ['result' => TreeToStringConverter::run($this->tree), 'steps' => $this->steps->flatten(1)]
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
            ? ['result' => TreeToStringConverter::run($this->tree, $mathjax = true), 'steps' => $this->steps->flatten(1)]
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
        $result = Simplifier::run($this->tree);

        $this->tree = $result['tree'];
        $this->steps->push($result['steps']);

        return $this;
    }

    /**
     * Substitute a value for another value.
     */
    public function substitute(array $replacements): self
    {
        $this->tree = Substitutor::run($this->tree, $replacements);

        $name = 'Substitute ';
        foreach ($replacements as $search => $replace) {
            $name .= "\\( {$search} \\) for \\( {$replace} \\) and ";
        }

        $this->steps->push([[
            'type' => 'substitute',
            'name' => substr($name, 0, -5),
            'result' => TreeToStringConverter::run($this->tree),
        ]]);

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

    /**
     * Solve an equation.
     */
    public function solveFor(string $letter): self
    {
        $this->tree = Solver::run($this->tree, $letter);

        $this->steps->push([[
            'type' => 'solve',
            'name' => "Solve for \\( {$letter} \\)",
            'result' => $letter . ' = ' . TreeToStringConverter::run($this->tree),
        ]]);

        return $this;
    }
}
