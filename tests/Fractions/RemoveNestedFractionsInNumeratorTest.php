<?php

use MathSolver\Fractions\RemoveNestedFractionsInNumerator;
use MathSolver\Utilities\StringToTreeConverter;

it('removes nested fractions in the numerator of a fraction', function () {
    $tree = StringToTreeConverter::run('frac[frac[2, 3], 5]');
    $result = RemoveNestedFractionsInNumerator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2, 5 * 3]'));
});

it('removes if the denominator is already a product', function () {
    $tree = StringToTreeConverter::run('frac[frac[2, 3], 5 * 4]');
    $result = RemoveNestedFractionsInNumerator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2, 5 * 4 * 3]'));
});

it('does not convert if there are no nested fractions', function () {
    $tree = StringToTreeConverter::run('frac[2, 3]');
    $result = RemoveNestedFractionsInNumerator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2, 3]'));
});

it('does not convert if the nested fraction is in the denominator', function () {
    $tree = StringToTreeConverter::run('frac[2, frac[3, 4]]');
    $result = RemoveNestedFractionsInNumerator::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2, frac[3, 4]]'));
});
