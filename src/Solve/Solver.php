<?php

namespace MathSolver\Solve;

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\Node;

class Solver
{
    public static function run(Node $equation, string $solveFor): Node
    {
        $leftMemberChildren = $equation
            ->children()
            ->first()
            ->children()
            ->filter(fn ($child) => $child->value() !== $solveFor)
            ->map(function ($child) {
                $times = new Node('*');
                $times->appendChild(new Node(-1));
                $times->appendChild($child);
                return $times;
            });

        $leftMember = $equation->children()->first();
        $equation->removeChild($leftMember);

        $rightMember = $equation->children()->last();
        $equation->removeChild($rightMember);

        $leftPlus = $equation->appendChild(new Node('+'));
        $leftBrackets = $leftPlus->appendChild(new Node('('));
        $leftBrackets->appendChild($leftMember);

        $rightPlus = $equation->appendChild(new Node('+'));
        $rightBrackets = $rightPlus->appendChild(new Node('('));
        $rightBrackets->appendChild($rightMember);

        $leftMemberChildren->each(fn ($child) => $leftPlus->appendChild(clone $child));
        $leftMemberChildren->each(fn ($child) => $rightPlus->appendChild(clone $child));

        $equation = Simplifier::run($equation)['tree'];

        $solution = $equation->children()->last();
        $solution->setParent(null);
        return $solution;
    }
}
