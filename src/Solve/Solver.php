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

    public Collection $steps;

    /**
     * Solve equations.
     */
    public static function run(Node $equation, string $solveFor): array
    {
        return (new self())->handle($equation, $solveFor);
    }

    /**
     * Bring everything except the searched letter to the right side.
     */
    public function handle(Node $equation, string $solveFor): array
    {
        $this->solveFor = $solveFor;
        $this->equation = $equation;
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
            'name' => 'Subtract',
            'result' => TreeToStringConverter::run($this->equation),
        ]);

        $result = Simplifier::run($this->equation);
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
                $power = new Node('^');
                $power->appendChild($child);
                $power->appendChild(new Node(-1));
                return $power;
            });

        $leftMember = $this->equation->children()->first();
        $this->equation->removeChild($leftMember);

        $rightMember = $this->equation->children()->last();
        $this->equation->removeChild($rightMember);

        $leftPlus = $this->equation->appendChild(new Node('*'));
        $leftBrackets = $leftPlus->appendChild(new Node('('));
        $leftBrackets->appendChild($leftMember);

        $rightPlus = $this->equation->appendChild(new Node('*'));
        $rightBrackets = $rightPlus->appendChild(new Node('('));
        $rightBrackets->appendChild($rightMember);

        $leftMemberChildren->each(fn ($child) => $leftPlus->appendChild(clone $child));
        $leftMemberChildren->each(fn ($child) => $rightPlus->appendChild(clone $child));

        $this->steps->push([
            'type' => 'solve',
            'name' => 'Divide',
            'result' => TreeToStringConverter::run($this->equation),
        ]);

        $result = Simplifier::run($this->equation);
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
