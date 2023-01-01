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
    public static function run(Node $node, bool $latex = false): string
    {
        // Return the value if the node is empty
        if ($node->children()->isEmpty()) {
            return $node->value();
        }

        $children = $node->children()->map(fn ($child) => self::run($child, $latex))->sort()->implode('');

        if ($node->value() === '*') {
            return Str::of($node->children()->map(fn ($child) => self::run($child, $latex))->implode('*'))
                ->replaceMatches('/([0-9a-z}])\*(\x5c)sqrt/', '$1$2sqrt') // Remove * symbol for roots
                ->replaceMatches('/(\x5cfrac{.+}{.+})\*([a-z])/', '$1$2') // Remove * symbol between fractions and letters
                ->replaceMatches('/([a-z0-9}])\*\(/', '$1(') // Remove * symbol between numbers/letters and (
                ->replace(')*(', ')(') // Remove * symbol between ) and (
                ->replaceMatches('/(?<=[a-z])\*(?=[a-z])/', '') // Remove * symbol with two letters
                ->replaceMatches('/([0-9])\*([a-z])/', '$1$2') // Remove * symbol with a number and a letter
                ->replaceMatches('/-1(\x5c|\()/', '-$1') // Replace "-1" by "-" (for example: "-1\sqrt{5}" -> "-\sqrt{5}")
                ->replaceMatches('/-1([a-z])/', '-$1'); // Replace "-1" by "-" (for example: "-1x" -> "-x")
        }

        if (in_array($node->value(), StringToTreeConverter::$functions)) {
            if ($latex && $node->value() === 'root') {
                $base = self::run($node->child(0), $latex);
                $degree = self::run($node->child(-1), $latex);

                return $degree == 2
                    ? "\\sqrt{{$base}}"
                    : "\\sqrt[{$degree}]{{$base}}";
            }

            if ($latex && $node->value() === 'frac') {
                $numerator = self::run($node->child(0), $latex);
                $denominator = self::run($node->child(-1), $latex);
                return "\\frac{{$numerator}}{{$denominator}}";
            }

            if (!$latex && $node->value() === 'frac') {
                return self::convertFractions($node);
            }

            if ($latex && $node->value() === 'deriv') {
                $inside = self::run($node->child(0), $latex);
                $respect = $node->child(1)?->value() ?? 'x';
                $isFraction = $node->contains('frac');
                return '\tfrac{d}{d' . $respect . '}' . ($isFraction ? '\left[' : '[') . $inside . ($isFraction ? '\right]' : ']');
            }

            if ($latex && $node->value() === 'log') {
                $base = self::run($node->child(1), $latex);
                $parameter = self::run($node->child(0), $latex);
                return '\log_{' . $base . '}[' . $parameter . ']';
            }

            $children = $node->children()->map(fn ($child) => self::run($child, $latex))->implode(',');
            $isFraction = $node->contains('frac') && $latex;
            return ($latex ? '\text{' . $node->value() . '}' : $node->value()) . ($isFraction ? '\left[' : '[') . $children . ($isFraction ? '\right]' : ']');
        }

        if ($latex && $node->value() === '^') {
            $base = self::run($node->child(0), $latex);

            $exponent = self::run($node->child(-1), $latex);

            if (str_starts_with($exponent, '(')) {
                $exponent = substr($exponent, 1);
            }
            if (str_ends_with($exponent, ')')) {
                $exponent = substr($exponent, 0, -1);
            }

            if ($node->child(0)->value() === 'root') {
                return "({$base})^{{$exponent}}";
            }
            if ($node->child(0)->value() === 'frac') {
                return "\\left({$base}\\right)^{{$exponent}}";
            }

            return "{$base}^{{$exponent}}";
        }

        // Return a string of all children when the node is not empty
        return $node->children() // Get all children
            ->map(fn ($child) => self::run($child, $latex)) // Get all children recursive
            ->map(fn ($child) => $child . $node->value()) // Appendd the parent node value
            ->pipe(fn ($children) => Str::of(implode('', $children->toArray()))) // Convert to a string
            ->rtrim($node->value()) // Remove the last parent node value (3+4+ -> 3+4)
            ->replace('+-', '-')
            ->when($node->value() == '(', function (string $string) use ($node, $latex) {  // Add brackets if necessary
                return $node->contains('frac') && $latex ? '\left('.$string.'\right)' : "({$string})";
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
