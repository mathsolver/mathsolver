<?php

use MathSolver\Exponents\MoveNegativeExponentsIntoFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('moves powers with negative exponents into fractions', function () {
    $tree = StringToTreeConverter::run('3^-1');
    $result = MoveNegativeExponentsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(1, 3)'));
});

it('moves powers with negative exponents into fractions with powers', function () {
    $tree = StringToTreeConverter::run('3^-5');
    $result = MoveNegativeExponentsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(1, 3^5)'));
});

it('does not run when there arent real numbers', function () {
    $tree = StringToTreeConverter::run('x^-2');
    $result = MoveNegativeExponentsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^-2'));

    $tree = StringToTreeConverter::run('5^x');
    $result = MoveNegativeExponentsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5^x'));
});

it('does not run with positive exponents', function () {
    $tree = StringToTreeConverter::run('7^3');
    $result = MoveNegativeExponentsIntoFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('7^3'));
});
