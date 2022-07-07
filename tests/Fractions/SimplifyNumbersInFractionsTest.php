<?php

use MathSolver\Fractions\SimplifyNumbersInFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('simplifies numeric factors in fractions', function () {
    $tree = StringToTreeConverter::run('frac[6x, 4]');
    $result = SimplifyNumbersInFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[3x, 2]'));
});

it('does not return the fraction if the new denominator is one', function () {
    $tree = StringToTreeConverter::run('frac[6x, 3]');
    $result = SimplifyNumbersInFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2x'));
});

it('does append a multiplication by one', function () {
    $tree = StringToTreeConverter::run('frac[3x, 3y]');
    $result = SimplifyNumbersInFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1x, 1y]'));
});

it('does not work with floats', function () {
    $tree = StringToTreeConverter::run('frac[0.5x, 1.5y]');
    $result = SimplifyNumbersInFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[0.5x, 1.5y]'));
});

it('does not run with only numbers', function () {
    $tree = StringToTreeConverter::run('frac[4, 6]');
    $result = SimplifyNumbersInFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[4, 6]'));
});

it('simplifies with additions', function () {
    $tree = StringToTreeConverter::run('frac[3x + 9, 6]');
    $result = SimplifyNumbersInFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1x + 3, 2]'));
});

it('simplifies with additions with product in denominator', function () {
    $tree = StringToTreeConverter::run('frac[15y + 12x, 9z]');
    $result = SimplifyNumbersInFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[5y + 4x, 3z]'));
});

it('simplifies with additions in both numerator and denominator', function () {
    $tree = StringToTreeConverter::run('frac[3a + 6b, 9c + 12d]');
    $result = SimplifyNumbersInFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1a + 2b, 3c + 4d]'));
});
