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
        $leftMemberChildren = $this->equation
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

        $leftMemberChildren->each(fn ($child) => $leftMember->appendChild(clone $child));
        $leftMemberChildren->each(fn ($child) => $rightMember->appendChild(clone $child));

        $this->steps->push([
            'type' => 'solve',
            'name' => $this->mathjax
                ? 'Add \( ' . $leftMemberChildren->map(fn ($child) => TreeToStringConverter::run($child, $this->mathjax))->implode(' and ') . ' \) to both sides'
                : 'Add ' . $leftMemberChildren->map(fn ($child) => TreeToStringConverter::run($child, $this->mathjax))->implode(' and ') . ' to both sides',
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
        $leftMemberChildren = $this->equation
            ->children()
            ->first()
            ->children()
            ->filter(fn ($child) => !$this->containsLetter($child))
            ->map(function ($child) {
                if (is_numeric($child->value())) {
                    $fraction = new Node('frac');
                    $fraction->appendChild(new Node(1));
                    $fraction->appendChild($child);
                    return $fraction;
                }

                $power = new Node('^');
                $power->appendChild($child);
                $power->appendChild(new Node(-1));
                return $power;
            });

        $leftMember = $this->equation->children()->first();
        $rightMember = $this->equation->children()->last();

        if ($rightMember->value() !== '*') {
            $this->equation->removeChild($rightMember);
            $rightTimes = $this->equation->appendChild(new Node('*'));
            $rightTimes->appendChild($rightMember);
            $rightMember = $rightTimes;
        }

        $leftMemberChildren->each(fn ($child) => $leftMember->appendChild(clone $child));
        $leftMemberChildren->each(fn ($child) => $rightMember->appendChild(clone $child));

        $this->steps->push([
            'type' => 'solve',
            'name' => $this->mathjax
                ? 'Multiply both sides by \( ' . $leftMemberChildren->map(fn ($child) => TreeToStringConverter::run($child, true))->implode(' \) and \( ') . ' \)'
                : 'Multiply both sides by ' . $leftMemberChildren->map(fn ($child) => TreeToStringConverter::run($child))->implode(' and '),
            'result' => TreeToStringConverter::run($this->equation, $this->mathjax),
        ]);

        $result = Simplifier::run($this->equation, $this->mathjax);
        collect($result['steps'])->each(fn ($step) => $this->steps->push($step));
        return $result['result'];
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
