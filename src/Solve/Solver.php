<?php

namespace MathSolver\Solve;

use Illuminate\Support\Collection;
use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\TreeToStringConverter;

class Solver
{
    /**
     * The letter to solve for and thus keep at the left side.
     */
    public string $solveFor;

    /**
     * The root node of the equation.
     */
    public Node $equation;

    /**
     * A collection with all recorded steps.
     */
    public Collection $steps;

    /**
     * Whether to use Mathjax for output.
     */
    public bool $mathjax;

    /**
     * Solve equations.
     */
    public static function run(Node $equation, string $solveFor, bool $mathjax = false): array
    {
        return (new self())->handle($equation, $solveFor, $mathjax);
    }

    /**
     * Bring everything except the searched letter to the right side.
     */
    public function handle(Node $equation, string $solveFor, bool $mathjax): array
    {
        $this->solveFor = $solveFor;
        $this->equation = $equation;
        $this->mathjax = $mathjax;
        $this->steps = new Collection();

        if ($this->equation->children()->first()->value() === '+') {
            $equation = $this->subtractFromBothSides();
        }

        if ($this->equation->children()->first()->value() === '*') {
            $equation = $this->divideFromBothSides();
        }

        return [
            'result' => $this->equation,
            'steps' => $this->steps->toArray(),
        ];
    }

    /**
     * Subtract everything except $solveFor from both sides and simplify.
     */
    protected function subtractFromBothSides(): Node
    {
        $termsToAdd = $this->equation
            ->children()
            ->first()
            ->children()
            ->filter(fn ($child) => !$this->containsLetter($child))
            ->map(function ($child) {
                if (is_numeric($child->value())) {
                    return new Node($child->value() * -1);
                }

                $times = new Node('*');
                $times->appendChild(new Node(-1));
                $times->appendChild($child);
                return $times;
            });

        $leftMember = $this->equation->children()->first();
        $rightMember = $this->equation->children()->last();

        if ($rightMember->value() !== '+') {
            $this->equation->removeChild($rightMember);
            $rightPlus = $this->equation->appendChild(new Node('+'));
            $rightPlus->appendChild($rightMember);
            $rightMember = $rightPlus;
        }

        $termsToAdd->each(fn ($child) => $leftMember->appendChild(clone $child));
        $termsToAdd->each(fn ($child) => $rightMember->appendChild(clone $child));

        $this->steps->push([
            'type' => 'solve',
            'name' => $this->mathjax
                ? 'Add \( ' . $termsToAdd->map(fn ($child) => TreeToStringConverter::run($child, $this->mathjax))->implode(' and ') . ' \) to both sides'
                : 'Add ' . $termsToAdd->map(fn ($child) => TreeToStringConverter::run($child, $this->mathjax))->implode(' and ') . ' to both sides',
            'result' => TreeToStringConverter::run($this->equation, $this->mathjax),
        ]);

        $result = Simplifier::run($this->equation, $this->mathjax);
        collect($result['steps'])->each(fn ($step) => $this->steps->push($step));
        return $result['result'];
    }

    /**
     * Divide everything except $solveFor from both sides and simplify.
     */
    protected function divideFromBothSides(): Node
    {
        $factorsToAdd = $this->equation
            ->children()
            ->first()
            ->children()
            ->filter(fn ($child) => !$this->containsLetter($child))
            ->map(fn ($child) => $this->wrapInInverseForDivision($child));

        $leftMember = $this->equation->children()->first();
        $rightMember = $this->equation->children()->last();

        if ($rightMember->value() !== '*') {
            if ($rightMember->value() === '+') {
                $this->equation->removeChild($rightMember);
                $rightTimes = $this->equation->appendChild(new Node('*'));
                $rightBrackets = $rightTimes->appendChild(new Node('('));
                $rightBrackets->appendChild($rightMember);
                $rightMember = $rightTimes;
            } else {
                $this->equation->removeChild($rightMember);
                $rightTimes = $this->equation->appendChild(new Node('*'));
                $rightTimes->appendChild($rightMember);
                $rightMember = $rightTimes;
            }
        }

        $factorsToAdd->each(fn ($child) => $leftMember->appendChild(clone $child));
        $factorsToAdd->each(fn ($child) => $rightMember->appendChild(clone $child));

        $this->steps->push([
            'type' => 'solve',
            'name' => $this->mathjax
                ? 'Multiply both sides by \( ' . $factorsToAdd->map(fn ($child) => TreeToStringConverter::run($child, true))->implode(' \) and \( ') . ' \)'
                : 'Multiply both sides by ' . $factorsToAdd->map(fn ($child) => TreeToStringConverter::run($child))->implode(' and '),
            'result' => TreeToStringConverter::run($this->equation, $this->mathjax),
        ]);

        $result = Simplifier::run($this->equation, $this->mathjax);
        collect($result['steps'])->each(fn ($step) => $this->steps->push($step));
        return $result['result'];
    }

    /**
     * Wrap a node in its inverse for division.
     *
     * Do this by taking the power of -1 of the node.
     */
    protected function wrapInInverseForDivision(Node $node): Node
    {
        if (is_numeric($node->value())) {
            $fraction = new Node('frac');

            $fraction->appendChild(new Node(1));
            $fraction->appendChild($node);

            return $fraction;
        }

        $power = new Node('^');

        $power->appendChild($node);
        $power->appendChild(new Node(-1));

        return $power;
    }

    /**
     * Check if a node or its children contains the searched letter.
     */
    protected function containsLetter(Node $node): bool
    {
        if ($node->value() === $this->solveFor) {
            return true;
        }

        return $node->children()->filter(fn ($child) => $this->containsLetter($child))->count() > 0;
    }
}
