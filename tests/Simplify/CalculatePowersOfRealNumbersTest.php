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

it('calculates powers of real negative numbers with brackets', function () {
    $tree = StringToTreeConverter::run('(-7)^4');
    $result = CalculatePowersOfRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2401'));

    $tree = StringToTreeConverter::run('(-6)^3');
    $result = CalculatePowersOfRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-216'));
});
