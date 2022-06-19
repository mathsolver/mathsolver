<?php

use MathSolver\Exponents\ConvertFractionsIntoNegativeExponents;
use MathSolver\Utilities\StringToTreeConverter;

it('converts fractions with letters into negative exponents', function () {
    $tree = StringToTreeConverter::run('frac[1, a]');
    $result = ConvertFractionsIntoNegativeExponents::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a^-1'));
});

it('converts with exponents', function () {
    $tree = StringToTreeConverter::run('frac[1, a^2]');
    $result = ConvertFractionsIntoNegativeExponents::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a^-2'));
});

it('converts with letters as powers', function () {
    $tree = StringToTreeConverter::run('frac[1, a^b]');
    $result = ConvertFractionsIntoNegativeExponents::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(a^b)^-1'));
});

it('multiplies with the numerator', function () {
    $tree = StringToTreeConverter::run('frac[3, a]');
    $result = ConvertFractionsIntoNegativeExponents::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3a^-1'));
});
