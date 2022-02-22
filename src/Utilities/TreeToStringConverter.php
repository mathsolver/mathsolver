<?php

namespace MathSolver\Utilities;

use Illuminate\Support\Str;

class TreeToStringConverter
{
    /**
     * Convert a tree to a string.
     *
     * This class uses recursion methods to get all children.
     */
    public static function run(Node $node, bool $mathjax = false): string
    {
        // Return the value if the node is empty
        if ($node->children()->isEmpty()) {
            return $node->value();
        }

        $children = $node->children()->map(fn ($child) => self::run($child, $mathjax))->sort()->implode('');

        if ($node->value() === '*') {
            return Str::of($node->children()->map(fn ($child) => self::run($child, $mathjax))->implode('*'))
                ->replaceMatches('/([0-9])\*(\x5c)sqrt/', '$1$2sqrt') // remove * symbol for roots
                ->replaceMatches('/(\x5cfrac{.+}{.+})\*([a-z])/', '$1$2') // remove * symbol between fractions and letters
                ->replaceMatches('/([a-z0-9])\*\(/', '$1(') // remove * symbol between numbers/letters and (
                ->replace(')*(', ')(') // remove * symbol between ) and (
                ->replaceMatches('/(?<=[a-z])\*(?=[a-z])/', '') // remove * symbol with two letters
                ->replaceMatches('/([0-9])\*([a-z])/', '$1$2') // remove * symbol with a number and a letter
                ->replaceMatches('/-1(\x5c)/', '-$1') // Replace "-1" by "-" (for example: "-1\sqrt{5}" -> "-\sqrt{5}")
                ->replaceMatches('/-1([a-z])/', '-$1'); // Replace "-1" by "-" (for example: "-1x" -> "-x")
        }

        if (in_array($node->value(), StringToTreeConverter::$functions)) {
            if ($mathjax && $node->value() === 'root') {
                $base = self::run($node->children()->first(), $mathjax);
                $degree = self::run($node->children()->last(), $mathjax);

                return $degree == 2
                    ? "\\sqrt{{$base}}"
                    : "\\sqrt[{$degree}]{{$base}}";
            }

            if ($mathjax && $node->value() === 'frac') {
                $numerator = self::run($node->children()->first(), $mathjax);
                $denominator = self::run($node->children()->last(), $mathjax);
                return "\\frac{{$numerator}}{{$denominator}}";
            }

            $children = $node->children()->map(fn ($child) => self::run($child, $mathjax))->implode(',');
            return "{$node->value()}({$children})";
        }

        if ($mathjax && $node->value() === '^') {
            $base = self::run($node->children()->first(), $mathjax);
            $exponent = self::run($node->children()->last(), $mathjax);
            return "{$base}^{{$exponent}}";
        }

        // Return a string of all children when the node is not empty
        return $node->children() // Get all children
            ->map(fn ($child) => self::run($child, $mathjax)) // Get all children recursive
            ->map(fn ($child) => $child . $node->value()) // Appendd the parent node value
            ->pipe(fn ($children) => Str::of(implode('', $children->toArray()))) // Convert to a string
            ->rtrim($node->value()) // Remove the last parent node value (3+4+ -> 3+4)
            ->replace('+-', '-')
            ->when($node->value() == '(', fn ($string) => "({$string})"); // Add brackets if necessary
    }
}
