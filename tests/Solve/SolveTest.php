<?php

use MathSolver\Solve\Solver;
use MathSolver\Utilities\StringToTreeConverter;

it('can solve linear equations', function (string $input, string $expected, string $solveFor = 'x') {
    $tree = StringToTreeConverter::run($input);
    $result = Solver::run($tree, $solveFor);
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
    ['5x = 25', '5'],
    ['3x = 27', '9'],
    ['8x = 40', '5'],
    ['28x = 1316', '47'],
    ['-2x = 4', '-2'],
    ['2x + 5 = 15', '5'],
    ['-5x + 8 = 53', '-9'],
    ['6 - x = 4', '2'],
    ['10x - 12 = -12', '0'],
    ['-16x = -4', 'frac(1, 4)'],
    ['14x + 3 = 2', 'frac(-1, 14)'],
    ['-6 - a = -4', '-2', 'a'],
    ['frac(1, 2) - 4a = frac(9, 2)', '-1', 'a'],
]);
