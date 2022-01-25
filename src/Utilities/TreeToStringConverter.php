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

        if (self::isProduct($node, $children)) {
            return $children;
        }

        if (in_array($node->value(), StringToTreeConverter::$functions)) {
            if ($mathjax && $node->value() === 'root') {
                $base = self::run($node->children()->first(), $mathjax);
                $degree = self::run($node->children()->last(), $mathjax);

                return $degree == 2
                    ? "\\sqrt{{$base}}"
                    : "\\sqrt[{$degree}]{{$base}}";
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
            ->when($node->value() == '(', fn ($string) => "({$string})"); // Add brackets if necessary
    }

    /**
     * Determine whether a string is a product.
     */
    protected static function isProduct(Node $node, string $children): bool
    {
        if (Str::match('/[0-9a-z-]+/', $children) !== $children) {
            return false;
        }

        if ($node->numericChildren()->count() > 1) {
            return false;
        }

        return !($node->value() !== '*');
    }
}
