<?php

use MathSolver\Fractions\ParseFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('parses fractions', function () {
    $tree = StringToTreeConverter::run('2/5');
    $result = ParseFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2, 5]'));
});

it('parses with letters', function () {
    $tree = StringToTreeConverter::run('x/y');
    $result = ParseFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[x, y]'));
});

it('parses fractions with brackets in numerator', function () {
    $tree = StringToTreeConverter::run('(x + 3) / 2');
    $result = ParseFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[(x + 3), 2]'));
});

it('parses fractions with brackets in denominator', function () {
    $tree = StringToTreeConverter::run('4 / (x - 2)');
    $result = ParseFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[4, (x - 2)]'));
});
