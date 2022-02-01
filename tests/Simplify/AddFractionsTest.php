<?php

use MathSolver\Simplify\AddFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('can add fractions', function () {
    $tree = StringToTreeConverter::run('frac(1, 5) + frac(2, 5)');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(3, 5)'));
});

it('can add fractions with different numerators', function () {
    $tree = StringToTreeConverter::run('frac(1, 3) + frac(1, 4)');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(7, 12)'));
});

it('does not add fractions with letters', function () {
    $tree = StringToTreeConverter::run('frac(x, 2) + frac(2, 4)');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(x, 2) + frac(2, 4)'));
});

it('adds fractions and numbers', function () {
    $tree = StringToTreeConverter::run('frac(1, 3) + 4');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(13, 3)'));
});
