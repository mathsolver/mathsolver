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
                ->replaceMatches('/([0-9a-z}])\*(\x5c)sqrt/', '$1$2sqrt') // Remove * symbol for roots
                ->replaceMatches('/(\x5cfrac{.+}{.+})\*([a-z])/', '$1$2') // Remove * symbol between fractions and letters
                ->replaceMatches('/([a-z0-9}])\*\(/', '$1(') // Remove * symbol between numbers/letters and (
                ->replace(')*(', ')(') // Remove * symbol between ) and (
                ->replaceMatches('/(?<=[a-z])\*(?=[a-z])/', '') // Remove * symbol with two letters
                ->replaceMatches('/([0-9])\*([a-z])/', '$1$2') // Remove * symbol with a number and a letter
                ->replaceMatches('/-1(\x5c)/', '-$1') // Replace "-1" by "-" (for example: "-1\sqrt{5}" -> "-\sqrt{5}")
                ->replaceMatches('/-1([a-z])/', '-$1'); // Replace "-1" by "-" (for example: "-1x" -> "-x")
        }

        if (in_array($node->value(), StringToTreeConverter::$functions)) {
            if ($mathjax && $node->value() === 'root') {
                $base = self::run($node->child(0), $mathjax);
                $degree = self::run($node->child(-1), $mathjax);

                return $degree == 2
                    ? "\\sqrt{{$base}}"
                    : "\\sqrt[{$degree}]{{$base}}";
            }

            if ($mathjax && $node->value() === 'frac') {
                $numerator = self::run($node->child(0), $mathjax);
                $denominator = self::run($node->child(-1), $mathjax);
                return "\\frac{{$numerator}}{{$denominator}}";
            }

            if (!$mathjax && $node->value() === 'frac') {
                return self::convertFractions($node);
            }

            if ($mathjax && $node->value() === 'deriv') {
                $inside = self::run($node->child(0), $mathjax);
                $respect = $node->child(1)?->value() ?? 'x';
                $isFraction = $node->contains('frac');
                return '\tfrac{d}{d' . $respect . '}' . ($isFraction ? '\left[' : '[') . $inside . ($isFraction ? '\right]' : ']');
            }

            if ($mathjax && $node->value() === 'log') {
                $base = self::run($node->child(1), $mathjax);
                $parameter = self::run($node->child(0), $mathjax);
                return '\log_{' . $base . '}[' . $parameter . ']';
            }

            $children = $node->children()->map(fn ($child) => self::run($child, $mathjax))->implode(',');
            $isFraction = $node->contains('frac') && $mathjax;
            return ($mathjax ? '\text{' . $node->value() . '}' : $node->value()) . ($isFraction ? '\left[' : '[') . $children . ($isFraction ? '\right]' : ']');
        }

        if ($mathjax && $node->value() === '^') {
            $base = self::run($node->child(0), $mathjax);

            $exponent = self::run($node->child(-1), $mathjax);

            if (str_starts_with($exponent, '(')) {
                $exponent = substr($exponent, 1);
            }
            if (str_ends_with($exponent, ')')) {
                $exponent = substr($exponent, 0, -1);
            }

            if ($node->child(0)->value() === 'root') {
                return "({$base})^{{$exponent}}";
            }

            return "{$base}^{{$exponent}}";
        }

        // Return a string of all children when the node is not empty
        return $node->children() // Get all children
            ->map(fn ($child) => self::run($child, $mathjax)) // Get all children recursive
            ->map(fn ($child) => $child . $node->value()) // Appendd the parent node value
            ->pipe(fn ($children) => Str::of(implode('', $children->toArray()))) // Convert to a string
            ->rtrim($node->value()) // Remove the last parent node value (3+4+ -> 3+4)
            ->replace('+-', '-')
            ->when($node->value() == '(', function (string $string) use ($node, $mathjax) {  // Add brackets if necessary
                return $node->contains('frac') && $mathjax ? '\left('.$string.'\right)' : "({$string})";
            });
    }

    /**
     * Convert a fraction-tree to a string.
     */
    public static function convertFractions(Node $fraction): string
    {
        // Wrap numerator in brackets
        if ($fraction->child(0)->children()->count() === 0) {
            $numerator = self::run($fraction->child(0));
        } else {
            $numerator = '(' . self::run($fraction->child(0)) . ')';
        }

        // Wrap denominator in brackets
        if ($fraction->child(1)->children()->count() === 0) {
            $denominator = self::run($fraction->child(1));
        } else {
            $denominator = '(' . self::run($fraction->child(1)) . ')';
        }

        // Return fraction syntax
        return $numerator . '/' . $denominator;
    }
}
