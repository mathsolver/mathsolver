<?php

use MathSolver\Simplify\MultiplyFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('can multiply fractions', function () {
    $tree = StringToTreeConverter::run('frac(1, 5) * frac(2, 5)');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(2, 25)'));

    $tree = StringToTreeConverter::run('frac(5, 2) * frac(7, 3)');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(35, 6)'));
});

it('leaves letters in the multiplication', function () {
    $tree = StringToTreeConverter::run('x * frac(2, 5) * frac(3, 5)');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('xfrac(6, 25)'));
});

it('multiplies with numbers', function () {
    $tree = StringToTreeConverter::run('7frac(3, 5)');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(21, 5)'));
});

it('does not run when the fraction has something else than numbers', function () {
    $tree = StringToTreeConverter::run('3frac(x, 5)');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3frac(x, 5)'));
});
