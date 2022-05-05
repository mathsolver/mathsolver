<?php

namespace MathSolver\Utilities;

use Illuminate\Support\Str;

class StringToTreeConverter
{
    /**
     * A list of functions to parse.
     *
     * @var array<string>
     */
    public static array $functions = [
        'cos',
        'deriv',
        'frac',
        'log',
        'rand',
        'root',
        'sin',
        'tan',
    ];

    /**
     * Convert a math expression to a math tree.
     */
    public static function run(string $expression): Node
    {
        $terms = Str::of($expression) // Get a stringable object
            ->replace(' ', '') // Remove spaces
            ->replaceMatches('/([a-z0-9π)])-/', '$1+-') // Replace - with +-
            ->replaceMatches('/-([^0-9])/', '-1$1') // Replace - with -1
            ->replaceMatches('/([0-9a-z.])\(/', '$1*(') // 5x(3y - 4) -> 5x * (3y - 4)
            ->replaceMatches('/\)([a-z0-9])/', ')*$1')
            ->replace(')(', ')*(')
            ->pipe(function ($string) { // Replace root* with root, and tan* with tan
                foreach (self::$functions as $function) {
                    $string = $string->replace("{$function}*", $function);
                }
                return $string;
            })
            ->replaceMatches('/[=|+|\/|*|^|(|)]/', ' $0 ') // Add spaces to operators
            ->replace(',', ' , ')
            ->explode(' ') // Explode on spaces
            ->flatMap(function ($term) { // Expand terms like 7xy to 7*x*y and xtan(45) to x*tan(45)
                // Check if it contains letters
                if (!Str::match('/[-]?[0-9.]*[a-z]+/', $term)) {
                    return [$term];
                }

                // Check for functions
                foreach (self::$functions as $function) {
                    if (str_contains($term, $function)) {
                        return $function === $term
                            ? [$term]
                            : [str_replace($function, '', $term), '*', $function];
                    }
                }

                $terms = array_merge($number = [preg_replace('/[^0-9-.]/', '', $term)], $letters = str_split(preg_replace('/[^a-z]/', '', $term)));
                return collect($terms)->filter(fn ($term) => !empty($term) || $term == '0')->flatMap(fn ($term) => [$term, '*'])->slice(0, -1)->toArray();
            })
            ->filter(fn ($term) => !empty($term) || $term == '0'); // Filter out empty values

        // Instantiate the first node
        $node = new Node($terms->first());
        $terms->shift();

        $bracketsAreClosed = false;

        // Loop over all terms
        /** @var string $term */
        foreach ($terms as $term) {
            if ($node->value() === '(' && !$bracketsAreClosed) {
                $node = $node->appendChild(new Node($term));
                continue;
            }

            if ($term === ')') {
                $node = $node->parent();

                while ($node->value() !== '(') {
                    $node = $node->parent();
                }

                $bracketsAreClosed = true;

                continue;
            }

            if (self::getPrecedence($term) < self::getPrecedence($node->value())) {
                $done = false;

                while (!$done) {
                    if ($node->parent()) {
                        if (self::getPrecedence($term, true) > self::getPrecedence($node->parent()->value(), true)) {
                            $parent = new Node($term);
                            $node->parent()->removeChild($node);
                            $node->parent()->appendChild($parent);
                            $parent->appendChild($node);
                            $node = $node->parent();

                            $done = true;
                        } else {
                            $node = $node->parent();

                            if ($node->value() == '(') {
                                $done = true;
                            }
                        }
                    } else {
                        $parent = new Node($term);
                        $parent->appendChild($node);
                        $node = $parent;

                        $done = true;
                    }

                    if ($node->value() == $term && in_array($node->value(), ['+', '*'])) {
                        $done = true;
                    }
                }
            } else {
                $node = $node->appendChild(new Node($term));
            }
        }

        // Return the root node
        return self::cleanFunctionBrackets($node->root());
    }

    /**
     * Get the precedence of a term or operator.
     */
    public static function getPrecedence(string $value, bool $nested = false): int
    {
        return match ($value) {
            '=' => 0,
            '+' => 6,
            '*' => 8,
            '/' => 8,
            '^' => 10,
            '(' => $nested ? 4 : 16,
            ',' => 2,
            default => in_array($value, self::$functions) ? 12 : 18,
        };
    }

    /**
     * Remove the brackets inside functions such as sin(90), tan(45) and root(9, 2).
     */
    protected static function cleanFunctionBrackets(Node $node): Node
    {
        // Run this function recursively
        $node->setChildren($node->children()->map(fn ($child) => self::cleanFunctionBrackets($child))->flatten());

        // Check if the node is a function
        if (in_array($node->value(), self::$functions)) {
            // Move all children of the brackets node to the function node
            $node->child(0)->children()->each(function ($child) use ($node) {
                $node->appendChild($child);
            });

            // Remove the brackets node
            $node->removeChild($node->child(0));
        }

        return $node;
    }
}
