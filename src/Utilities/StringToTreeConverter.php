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
    protected static array $functions = [
        'root',
        'sin',
        'cos',
        'tan',
    ];

    /**
     * Convert a math expression to a math tree.
     */
    public static function run(string $expression): Node
    {
        $terms = Str::of($expression) // Get a stringable object
            ->replace(' ', '') // Remove spaces
            ->replaceMatches('/([a-z0-9)])-/', '$1+-') // Replace - with +-
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
            ->replaceMatches('/[+|\/|*|^|(|)]/', ' $0 ') // Add spaces to operators
            ->replace(',', ' , ')
            ->explode(' ') // Explode on spaces
            ->flatMap(function ($term) { // Expand terms like 7xy or abc to 7*y and a*b*c
                if (!Str::match('/[-]?[0-9.]*[a-z]+/', $term)) {
                    return [$term];
                }

                // check for functions
                if (in_array(preg_replace('/[0-9]+([a-z]+)/', '$1', $term), self::$functions)) {
                    if (preg_replace('/[0-9]+([a-z]+)/', '$1', $term) === $term) {
                        return [$term];
                    }
                    return [preg_replace('/([0-9]+)[a-z]+/', '$1', $term), '*', preg_replace('/[0-9]+([a-z]+)/', '$1', $term)];
                }

                $terms = array_merge($number = [preg_replace('/[^0-9-.]/', '', $term)], $letters = str_split(preg_replace('/[^a-z]/', '', $term)));
                return collect($terms)->filter(fn ($term) => !empty($term) || $term == '0')->flatMap(fn ($term) => [$term, '*'])->slice(0, -1)->toArray();
            })
            ->filter(fn ($term) => !empty($term) || $term == '0'); // Filter out empty values

        // Instantiate the first node
        $node = new Node($terms->first());
        $terms->shift();

        // Loop over all terms
        foreach ($terms as $term) {
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

                            if ($term === ')' && in_array($node->value(), self::$functions)) {
                                $done = true;
                                continue;
                            }

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
                if ($term === '(' && in_array($node->value(), self::$functions)) {
                    continue;
                }

                $node = $node->appendChild(new Node($term));
            }
        }

        // Return the root node
        return $node->root();
    }

    /**
     * Get the precedence of a term or operator.
     */
    public static function getPrecedence(string $value, bool $nested = false): int
    {
        return match ($value) {
            '+' => 2,
            '*' => 4,
            '/' => 4,
            '^' => 5,
            '(' => $nested ? 1 : 19,
            ')' => $nested ? 1 : 18,
            default => in_array($value, self::$functions) ? 17 : 20,
        };
    }
}
