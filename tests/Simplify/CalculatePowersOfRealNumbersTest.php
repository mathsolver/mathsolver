<?php

use MathSolver\Simplify\CalculatePowersOfRealNumbers;
use MathSolver\Utilities\StringToTreeConverter;

it('calculates powers of real numbers', function () {
    $tree = StringToTreeConverter::run('5^2');
    $result = CalculatePowersOfRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('25'));

    $tree = StringToTreeConverter::run('8^3');
    $result = CalculatePowersOfRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('512'));
});

it('calculates powers of real negative numbers', function () {
    $tree = StringToTreeConverter::run('-5^2');
    $result = CalculatePowersOfRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-25'));

    $tree = StringToTreeConverter::run('-8^3');
    $result = CalculatePowersOfRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-512'));
});
