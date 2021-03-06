<?php

use MathSolver\Fractions\RemoveNestedFractionsInDenominator;
use MathSolver\Utilities\StringToTreeConverter;

it('removes nested fractions in the denominator of a fraction', function () {
    $tree = StringToTreeConverter::run('frac[2, frac[3, 5]]');
    $result = RemoveNestedFractionsInDenominator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2 * 5, 3]'));
});

it('removes if the numerator is already a product', function () {
    $tree = StringToTreeConverter::run('frac[2 * 4, frac[5, 3]]');
    $result = RemoveNestedFractionsInDenominator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2 * 4 * 3, 5]'));
});

it('does not convert if there are no nested fractions', function () {
    $tree = StringToTreeConverter::run('frac[2, 3]');
    $result = RemoveNestedFractionsInDenominator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2, 3]'));
});

it('does not convert if the nested fraction is in the numerator', function () {
    $tree = StringToTreeConverter::run('frac[frac[3, 4], 2]');
    $result = RemoveNestedFractionsInDenominator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[frac[3, 4], 2]'));
});

it('adds brackets if needed', function () {
    $tree = StringToTreeConverter::run('frac[x + 2, frac[3, 5]]');
    $result = RemoveNestedFractionsInDenominator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[(x + 2) * 5, 3]'));
});
