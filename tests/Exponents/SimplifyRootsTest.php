<?php

use MathSolver\Exponents\SimplifyRoots;
use MathSolver\Utilities\StringToTreeConverter;

it('can simplify a root', function () {
    $tree = StringToTreeConverter::run('sqrt(20)');
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2 * sqrt(5)'));
});

it('wont return a multiplication if the coefficient is one', function () {
    $tree = StringToTreeConverter::run('sqrt(6)');
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('sqrt(6)'));
});

it('wont return a root sign if the number inside the root is one', function () {
    $tree = StringToTreeConverter::run('sqrt(9)');
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3'));
});

it('wont return a multiplication is the parent is a multiplication', function () {
    $tree = StringToTreeConverter::run('2 * sqrt(8)');
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2 * 2 * sqrt(2)'));
});

it('can simplify one-degree-roots', function () {
    $tree = StringToTreeConverter::run('root(8, 1)');
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('8'));
});

it('wont simplify roots of negative numbers with even exponents', function () {
    $tree = StringToTreeConverter::run('root(-16, 2)');
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('root(-16, 2)'));
});

it('adds brackets if it is in a power', function () {
    $tree = StringToTreeConverter::run('2^sqrt(8)');
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2^(2*sqrt(2))'));
});

it('can simplify roots of negative numbers with odd exponents', function (string $input, string $output) {
    $tree = StringToTreeConverter::run($input);
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run($output));
})->with([
    ['root(-27, 3)', '-3'],
    ['root(-9, 3)', 'root(-9, 3)'],
    ['root(-16, 3)', '-2cbrt(2)'],
    ['2root(-16, 3)', '2 * -2cbrt(2)'],
]);

it('can simplify square roots', function ($start, $outside, $inside) {
    $tree = StringToTreeConverter::run("root({$start} , 2)");
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run("{$outside} * root({$inside}, 2)"));
})->with([
    [18, 3, 2],
    [90, 3, 10],
    [98, 7, 2],
    [549, 3, 61],
    [620, 2, 155],
    [968, 22, 2],
    [1292, 2, 323],
    [1341, 3, 149],
    [1690, 13, 10],
    [3825, 15, 17],
]);

it('can simplify cube roots', function ($start, $outside, $inside) {
    $tree = StringToTreeConverter::run("root({$start} , 3)");
    $result = SimplifyRoots::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run("{$outside} * root({$inside}, 3)"));
})->with([
    [48, 2, 6],
    [192, 4, 3],
    [875, 5, 7],
    [1372, 7, 4],
    [2048, 8, 4],
    [4608, 8, 9],
    [6144, 8, 12],
    [6750, 15, 2],
    [10206, 9, 14],
    [96026, 19, 14],
]);
