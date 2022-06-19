<?php

use MathSolver\Sorting\SortTerms;
use MathSolver\Utilities\StringToTreeConverter;

it('sorts terms', function () {
    $tree = StringToTreeConverter::run('5 + x');
    $result = SortTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x + 5'));
});

it('sorts letters', function () {
    $tree = StringToTreeConverter::run('c + a');
    $result = SortTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a + c'));
});

it('sorts multiplications', function () {
    $tree = StringToTreeConverter::run('4y + 2x');
    $result = SortTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2x + 4y'));
});

it('sorts multiplications without letters', function () {
    $tree = StringToTreeConverter::run('5 + 3 * 7');
    $result = SortTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 * 7 + 5'));
});

it('sorts multiplications with two letters', function () {
    $tree = StringToTreeConverter::run('xy + z + 6');
    $result = SortTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('z + xy + 6'));
});

it('sorts powers first', function () {
    $tree = StringToTreeConverter::run('5 + x^2');
    $result = SortTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^2 + 5'));
});

it('sorts with deriv functions', function () {
    $tree = StringToTreeConverter::run('deriv[2x] + deriv[x^2]');
    $result = SortTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv[x^2] + deriv[2x]'));
});
