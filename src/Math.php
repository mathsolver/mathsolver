<?php

namespace MathSolver;

use Illuminate\Support\Collection;
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
        'steps' => false,
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

        return $this->options['steps']
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
     * - `steps (bool)` whether to record steps
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

        $this->steps->push([
            'type' => 'substitute',
            'name' => 'Substitute ' . collect($replacements)->map(fn ($replace, $search) => $this->options['mathjax'] ? "\\( {$search} \\) for \\( {$replace} \\)" : "{$search} for {$replace}")->implode(' and '),
            'result' => TreeToStringConverter::run($this->tree),
        ]);

        return $this;
    }

    /**
     * Simplify the expression.
     */
    public function simplify(): self
    {
        $result = Runner::run($this->tree, $this->options['mathjax']);

        $this->tree = $result['result'];

        collect($result['steps'])->each(fn ($step) => $this->steps->push($step));

        return $this;
    }
}
