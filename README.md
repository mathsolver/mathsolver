# Math Solver

PHP package to simplify mathematical expressions and solve equations.

## Example

```php
use MathSolver\Math;

Math::from('3x^2 + 2x * 4x')->simplify()->string(); // 11x^2

Math::from('2x + 3')->substitute(['x' => 5])->simplify()->string(); // 13

Math::from('2x + 16')->solveFor('x')->string(); // x = 8
```

## Table of contents

-   [Example](#example)
-   [Table of contents](#table-of-contents)
-   [Installation](#installation)
-   [Usage](#usage)
    -   [Simplify](#simplify)
    -   [Substitute values](#substitute-values)
    -   [Solve for a letter](#solve-for-a-letter)
-   [Configuration](#configuration)
    -   [Mathjax](#mathjax)
    -   [Steps](#steps)
-   [Mathematical syntax](#mathematical-syntax)

## Installation

Not yet installable.

## Usage

First, load an expression with the `from` method.

```php
use MathSolver\Math;

Math::from('2x + 3x');
```

Then, you can chain one of these methods:

### Simplify

Simplify the expression.

```php
use MathSolver\Math;

Math::from('2x + 3x')->simplify()->string(); // 5x
```

### Substitute values

Substitute a value into the expression.

```php
use MathSolver\Math;

Math::from('2x + 3x')->substitute(['x' => 4])->string(); // 2(4) + 3(4)
```

The `substitute` methods pairs nicely with the `simplify` method.

```php
use MathSolver\Math;

Math::from('2x + 3x')->substitute(['x' => 4])->simplify()->string(); // 20
```

### Solve for a letter

Solve the equation for a given letter.

```php
use MathSolver\Math;

Math::from('2x + 3 = 11')->solveFor('x')->string(); // x = 4
```

## Configuration

You can call the `config` method for any configuration options.

```php
use MathSolver\Math;

Math::from('2x + 3 = 11')->config(['mathjax' => true, 'steps' => false])->...
```

These are all available options in short:

| Name      | Type   | Default | Description                        |
| --------- | ------ | ------- | ---------------------------------- |
| `mathjax` | `bool` | `false` | Whether to return Mathjax output   |
| `steps`   | `bool` | `false` | Whether to record and return steps |

### Mathjax

[Mathjax](https://www.mathjax.org/) is a tool to display mathematical expressions in your browser. Set this option to true so Math Solver will return responses in Mathjax syntax instead of the default one.

```php
use MathSolver\Math;

Math::from('root(18, 2)')->config(['mathjax' => true])->simplify()->string(); // 3\sqrt{2}
```

### Steps

Settings this value to true will Math Solver make record every step.

```php
use MathSolver\Math;

Math::from('root(18, 2) + root(32, 2)')->config(['steps' => true])->simplify()->string();

// [
//     'result' => '7root(2,2)',
//     'steps' => [
//         ['type' => 'simplify', 'name' => 'Simplify roots', 'result' => '3root(2,2)+4root(2,2)'],
//         ['type' => 'simplify', 'name' => 'Add like terms', 'result' => '7root(2,2)']
//     ]
// ]
```

## Mathematical syntax

Here are some functions that require another input syntax than you may be used to:

-   **Powers**: five to the power of three is `5^3`
-   **Roots**: the cube root of 15 is `root(15, 3)`
-   **Fractions**: two-thirds is `frac(2, 3)`