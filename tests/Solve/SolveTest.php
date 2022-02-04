<?php

use MathSolver\Solve\Solver;
use MathSolver\Utilities\StringToTreeConverter;

it('can solve linear equations by adding', function (string $input, string $expected) {
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
]);
