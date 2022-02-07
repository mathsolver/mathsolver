<?php

namespace MathSolver\Solve;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
        // setup this class
        $this->solveFor = $solveFor;
        $this->equation = $equation;
        $this->mathjax = $mathjax;
        $this->steps = new Collection();

        // subtract everything except the searched letter
        if ($this->equation->children()->first()->value() === '+') {
            $equation = $this->subtractFromBothSides();
        }

        // divide everything except the searched letter
        if ($this->equation->children()->first()->value() === '*') {
            $equation = $this->divideFromBothSides();
        }

        // return results
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
            ->map(fn ($child) => $this->wrapInInverseForSubstraction($child));

        $leftMember = $this->equation->children()->first();
        $rightMember = $this->equation->children()->last();

        if ($rightMember->value() !== '+') {
            $this->equation->removeChild($rightMember);
            $rightPlus = $this->equation->appendChild(new Node('+'));
            $rightPlus->appendChild($rightMember);
            $rightMember = $rightPlus;
        }

        $termsToAdd
            ->each(fn ($child) => $leftMember->appendChild(clone $child))
            ->each(fn ($child) => $rightMember->appendChild(clone $child));

        $this->recordSteps($termsToAdd, 'Add {terms} to both sides');

        $result = Simplifier::run($this->equation, $this->mathjax);
        collect($result['steps'])->each(fn ($step) => $this->steps->push($step));
        return $result['result'];
    }

    /**
     * Wrap a node in its inverse for subtraction.
     *
     * Do this by multiplying the node by -1.
     */
    protected function wrapInInverseForSubstraction(Node $node): Node
    {
        // multiply directly if the node is a number
        if (is_numeric($node->value())) {
            return new Node($node->value() * -1);
        }

        $times = new Node('*');

        $times->appendChild(new Node(-1));
        $times->appendChild($node);

        return $times;
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
        $rightMember = $this->wrapRightMemberInMultiplication($this->equation->children()->last());

        $factorsToAdd
            ->each(fn ($child) => $leftMember->appendChild(clone $child))
            ->each(fn ($child) => $rightMember->appendChild(clone $child));

        $this->recordSteps($factorsToAdd, 'Multiply both sides by {terms}');

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
        // wrap in fraction if the node is a number
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
     * Make sure the right member of the equation is a multiplication.
     *
     * If not, wrap it in a multiplication.
     */
    protected function wrapRightMemberInMultiplication(Node $topNode): Node
    {
        // if it is a multiplication already, do nothing
        if ($topNode->value() === '*') {
            return $topNode;
        }

        // if it is an addition, wrap the addition in brackets,
        // for example: `5` multiplied by `x + 4` becomes `5(x + 4)`
        if ($topNode->value() === '+') {
            $this->equation->removeChild($topNode);
            $times = $this->equation->appendChild(new Node('*'));
            $brackets = $times->appendChild(new Node('('));
            $brackets->appendChild($topNode);
            return $times;
        }

        // if it is something else, just make the multiplication
        // the top node and return it
        $this->equation->removeChild($topNode);
        $times = $this->equation->appendChild(new Node('*'));
        $times->appendChild($topNode);
        return $times;
    }

    /**
     * Record steps for subtraction or division.
     */
    protected function recordSteps(Collection $terms, string $sentence): void
    {
        $name = $terms
            ->map(fn ($child) => TreeToStringConverter::run($child, $this->mathjax))
            ->pipe(fn ($terms) => Str::of(implode($this->mathjax ? ' \) and \( ' : ' and ', $terms->toArray())))
            ->when($this->mathjax, fn ($string) => Str::of($sentence)->replace('{terms}', "\\( {$string} \\)"))
            ->when(!$this->mathjax, fn ($string) => Str::of($sentence)->replace('{terms}', $string));

        $this->steps->push([
            'type' => 'solve',
            'name' => $name,
            'result' => TreeToStringConverter::run($this->equation, $this->mathjax),
        ]);
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
