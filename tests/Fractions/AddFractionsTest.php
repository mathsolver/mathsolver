<?php

use MathSolver\Fractions\AddFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('can add fractions', function () {
    $tree = StringToTreeConverter::run('frac[1, 5] + frac[2, 5]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[3, 5]'));
});

it('can add fractions with different numerators', function () {
    $tree = StringToTreeConverter::run('frac[1, 3] + frac[1, 4]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[7, 12]'));
});

it('does not add fractions with letters', function () {
    $tree = StringToTreeConverter::run('frac[x, 2] + frac[2, 4]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[x, 2] + frac[2, 4]'));
});

it('adds fractions and numbers', function () {
    $tree = StringToTreeConverter::run('frac[1, 3] + 4');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[13, 3]'));
});

it('can add up more than two fractions', function () {
    $tree = StringToTreeConverter::run('frac[1, 3] + frac[3, 4] + frac[2, 8]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[4, 3]'));
});

it('does not reorder', function () {
    $tree = StringToTreeConverter::run('frac[1, 3] + x');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1, 3] + x'));
});

it('does not run with floats in fractions', function () {
    $tree = StringToTreeConverter::run('frac[1, 3.5] + 2');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1, 3.5] + 2'));
});

it('does not run with floats', function () {
    $tree = StringToTreeConverter::run('frac[1, 2] + 0.5');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1, 2] + 0.5'));
});
