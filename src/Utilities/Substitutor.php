<?php

namespace MathSolver\Utilities;

class Substitutor
{
    /**
     * Substitute a value for another.
     */
    public static function run(Node $node, array $replacements): Node
    {
        $node->setChildren($node->children()->map(fn ($child) => self::run($child, $replacements))->flatten());

        if (!array_key_exists($node->value(), $replacements)) {
            return $node;
        }

        $brackets = new Node('(');
        $brackets->appendChild(StringToTreeConverter::run($replacements[$node->value()]));
        return $brackets;
    }
}
