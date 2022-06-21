<?php

use MathSolver\Exponents\CalculateLogarithms;
use MathSolver\Utilities\StringToTreeConverter;

it('can calculate a logarithm', function () {
    $tree = StringToTreeConverter::run('log[8, 2]');
    $result = CalculateLogarithms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3'));
});

it('wont calculate if there isnt an aboslute value', function () {
    $tree = StringToTreeConverter::run('log[10, 2]');
    $result = CalculateLogarithms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('log[10, 2]'));
});

it('can calculate logarithmes of decimals', function () {
    $tree = StringToTreeConverter::run('log[3, 9]');
    $result = CalculateLogarithms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1, 2]'));
});

test('the base defaults to 10', function () {
    $tree = StringToTreeConverter::run('log[1000]');
    $result = CalculateLogarithms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3'));
});
