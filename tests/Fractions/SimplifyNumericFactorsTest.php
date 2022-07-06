<?php

use MathSolver\Fractions\SimplifyNumericFactors;
use MathSolver\Utilities\StringToTreeConverter;

it('simplifies numeric factors in fractions', function () {
    $tree = StringToTreeConverter::run('frac[6x, 4]');
    $result = SimplifyNumericFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[3x, 2]'));
});

it('does not return the fraction if the new denominator is one', function () {
    $tree = StringToTreeConverter::run('frac[6x, 3]');
    $result = SimplifyNumericFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2x'));
});

it('does append a multiplication by one', function () {
    $tree = StringToTreeConverter::run('frac[3x, 3y]');
    $result = SimplifyNumericFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1x, 1y]'));
});

it('does not work with floats', function () {
    $tree = StringToTreeConverter::run('frac[0.5x, 1.5y]');
    $result = SimplifyNumericFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[0.5x, 1.5y]'));
});

it('does not run with only numbers', function () {
    $tree = StringToTreeConverter::run('frac[4, 6]');
    $result = SimplifyNumericFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[4, 6]'));
});
