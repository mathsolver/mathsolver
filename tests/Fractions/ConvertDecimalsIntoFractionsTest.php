<?php

use MathSolver\Fractions\ConvertDecimalsIntoFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('converts decimals into fractions', function () {
    $tree = StringToTreeConverter::run('0.5');
    $result = ConvertDecimalsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(5, 10)'));
});

it('converts decimals into fractions with multiple decimal places', function () {
    $tree = StringToTreeConverter::run('0.25');
    $result = ConvertDecimalsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(25, 100)'));
});

it('leaves whole numbers', function () {
    $tree = StringToTreeConverter::run('1.125');
    $result = ConvertDecimalsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(1125, 1000)'));
});

it('does not convert whole numbers', function () {
    $tree = StringToTreeConverter::run('2');
    $result = ConvertDecimalsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2'));
});
