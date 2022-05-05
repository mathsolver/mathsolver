<?php

use MathSolver\Sorting\SortFactors;
use MathSolver\Utilities\StringToTreeConverter;

it('sorts factors', function () {
    $tree = StringToTreeConverter::run('b * a');
    $result = SortFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('ab'));
});

it('sorts numbers before letters', function () {
    $tree = StringToTreeConverter::run('x * 6');
    $result = SortFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('6x'));
});

it('sorts powers after letters', function () {
    $tree = StringToTreeConverter::run('4^3 * a');
    $result = SortFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a * 4^3'));
});

it('sorts letters with powers before other letters', function () {
    $tree = StringToTreeConverter::run('b * a^3');
    $result = SortFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a^3b'));
});
