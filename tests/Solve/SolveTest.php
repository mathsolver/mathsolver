<?php

use MathSolver\Solve\Solver;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;

it('can solve linear equations', function (string $input, string $expected) {
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
    ['-6 - x = -4', '-2'],
    ['frac(1, 2) - 4x = frac(9, 2)', '-1'],
    ['4x + 17 = 29', '3'],
    ['-3x + 1 = 16', '-5'],
    ['7x + 1 = 8', '1'],
    ['6x + 24 = 0', '-4'],
    ['9x + 8 = 8', '0'],
    ['3x + 5 = 23', '6'],
    ['2x + 8 = -20', '-14'],
    ['-5x + 7 = 17', '-2'],
    ['2x + frac(1, 2) = frac(13, 2)', '3'],
    ['4x = 20', '5'],
    ['x + 4 = 20', '16'],
    ['4x = 3', 'frac(3, 4)'],
    ['x + 4 = 3', '-1'],
    ['2x = -20', '-10'],
    ['x + 2 = -20', '-22'],
    ['x + 8 = -9', '-17'],
    ['8x = -80', '-10'],
    ['8 + 5x = 28', '4'],
    ['2 + 3x = 2', '0'],
    ['25 + x = 15', '-10'],
    ['25x = 15', 'frac(3, 5)'],
]);

it('can solve for other letters', function () {
    $tree = StringToTreeConverter::run('2a + 4 = 10');
    $result = Solver::run($tree, 'a');
    expect($result)->toEqual(new Node(3));

    $tree = StringToTreeConverter::run('7b + 3 = 31');
    $result = Solver::run($tree, 'b');
    expect($result)->toEqual(new Node(4));
});
