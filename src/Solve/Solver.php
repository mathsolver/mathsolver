<?php

namespace MathSolver\Solve;

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\Node;

class Solver
{
    public static function run(Node $equation, string $solveFor): Node
    {
        if ($equation->children()->first()->value() === '+') {
            $equation = self::subtractFromBothSides($equation, $solveFor);
        }

        if ($equation->children()->first()->value() === '*') {
            $equation = self::divideFromBothSides($equation, $solveFor);
        }

        $solution = $equation->children()->last();
        $solution->setParent(null);
        return $solution;
    }

    protected static function subtractFromBothSides(Node $equation, string $solveFor): Node
    {
        $leftMemberChildren = $equation
            ->children()
            ->first()
            ->children()
            ->filter(fn ($child) => !self::containsLetter($child, $solveFor))
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

        return Simplifier::run($equation)['result'];
    }

    protected static function divideFromBothSides(Node $equation, string $solveFor): Node
    {
        $leftMemberChildren = $equation
            ->children()
            ->first()
            ->children()
            ->filter(fn ($child) => !self::containsLetter($child, $solveFor))
            ->map(function ($child) {
                $power = new Node('^');
                $power->appendChild($child);
                $power->appendChild(new Node(-1));
                return $power;
            });

        $leftMember = $equation->children()->first();
        $equation->removeChild($leftMember);

        $rightMember = $equation->children()->last();
        $equation->removeChild($rightMember);

        $leftPlus = $equation->appendChild(new Node('*'));
        $leftBrackets = $leftPlus->appendChild(new Node('('));
        $leftBrackets->appendChild($leftMember);

        $rightPlus = $equation->appendChild(new Node('*'));
        $rightBrackets = $rightPlus->appendChild(new Node('('));
        $rightBrackets->appendChild($rightMember);

        $leftMemberChildren->each(fn ($child) => $leftPlus->appendChild(clone $child));
        $leftMemberChildren->each(fn ($child) => $rightPlus->appendChild(clone $child));

        return Simplifier::run($equation)['result'];
    }

    protected static function containsLetter(Node $node, string $solveFor): bool
    {
        if ($node->value() === $solveFor) {
            return true;
        }

        return $node->children()->filter(fn ($child) => self::containsLetter($child, $solveFor))->count() > 0;
    }
}
