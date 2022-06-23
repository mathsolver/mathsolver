<?php

use MathSolver\Fractions\AddFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('can add fractions', function () {
    $tree = StringToTreeConverter::run('frac[1, 4] + frac[2, 4]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1 * 4 + 2 * 4, 4 * 4]'));
});

it('can add fractions with letters', function () {
    $tree = StringToTreeConverter::run('frac[a, b] + frac[c, d]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[ad + cb, bd]'));
});

it('can add fractions with expressions', function () {
    $tree = StringToTreeConverter::run('frac[1 + x, 4] + frac[2, 4]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[(1 + x) * 4 + 2 * 4, 4 * 4]'));
});

it('works with a denominator of one', function () {
    $tree = StringToTreeConverter::run('2 + frac[1, 2]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2*2 + 1*1, 1*2]'));
});

it('works with multiple fractions', function () {
    $tree = StringToTreeConverter::run('frac[a, b] + frac[c, d] + frac[e, f]');
    $result = AddFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[adf + cbf + ebd, bdf]'));
});
