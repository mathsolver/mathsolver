<?php

use MathSolver\Fractions\MultiplyFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('can multiply fractions', function () {
    $tree = StringToTreeConverter::run('frac[5, 8] * frac[3, 7]');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[5 * 3, 8 * 7]'));
});

it('can multiply letter-fractions', function () {
    $tree = StringToTreeConverter::run('frac[a, b] * frac[c, d]');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[ac, bd]'));
});

it('can multiply expressions', function () {
    $tree = StringToTreeConverter::run('frac[x + 2, 4] * frac[5, 6]');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[(x + 2) * 5, 4 * 6]'));
});

it('can multiply by a denominator of one', function () {
    $tree = StringToTreeConverter::run('6 * frac[3, 7]');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[6 * 3, 1 * 7]'));
});

it('can multiply multiple fractions', function () {
    $tree = StringToTreeConverter::run('frac[a, b] * frac[c, d] * frac[e, f]');
    $result = MultiplyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[ace, bdf]'));
});
