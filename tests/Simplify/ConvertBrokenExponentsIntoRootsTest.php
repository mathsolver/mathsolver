<?php

use MathSolver\Simplify\ConvertBrokenExponentsIntoRoots;
use MathSolver\Utilities\StringToTreeConverter;

it('converts broken exponents into roots', function () {
    $tree = StringToTreeConverter::run('x^2.5');
    $result = ConvertBrokenExponentsIntoRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^2 * root(x, 2)'));
});

it('does not add the whole exponent if it is zero', function () {
    $tree = StringToTreeConverter::run('5^0.5');
    $result = ConvertBrokenExponentsIntoRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('root(5, 2)'));
});

it('doesnt run when the exponent is a whole number', function () {
    $tree = StringToTreeConverter::run('y^3');
    $result = ConvertBrokenExponentsIntoRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('y^3'));
});

it('doesnt return a multiplication is the parent is a multiplication', function () {
    $tree = StringToTreeConverter::run('4a^3.25');
    $result = ConvertBrokenExponentsIntoRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4 * a^3 * root(a, 4)'));
});

it('works with negative exponents', function () {
    $tree = StringToTreeConverter::run('z^-1.5');
    $result = ConvertBrokenExponentsIntoRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('z^-2 * root(z, 2)'));
});
