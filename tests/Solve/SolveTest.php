<?php

use MathSolver\Solve\Solver;
use MathSolver\Utilities\StringToTreeConverter;

it('can solve linear equations by subtracting', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Solver::run($tree, 'x');
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['x + 3 = 6', '3'],
    ['x + 9 = 10', '1'],
    ['x - 3 = 9', '12'],
    ['x + 4 = -8', '-12'],
    ['5 + x = 8', '3'],
    ['x = 6', '6'],
    ['x + 3 = y + 4', 'y + 1'],
    ['x + 7 = 10', '3'],
    ['x + 1 = 0', '-1'],
    ['9 + x = 11', '2'],
]);

it('can solve linear equations by dividing', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Solver::run($tree, 'x');
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5x = 25', '5'],
    ['3x = 27', '9'],
    ['8x = 40', '5'],
    ['28x = 1316', '47'],
    ['-2x = 4', '-2'],
]);

it('can solve linear equations by substracting and dividing', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Solver::run($tree, 'x');
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['2x + 5 = 15', '5'],
    ['-5x + 8 = 53', '-9'],
    ['6 - x = 4', '2'],
]);
