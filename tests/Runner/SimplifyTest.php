<?php

use MathSolver\Runner;
use MathSolver\Utilities\StringToTreeConverter;

it('simplifies products and adds like terms', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['p + p + p + p', '4p'],
    ['x + x', '2x'],
    ['a + a + a + a + a + a + a + a', '8a'],
    ['r + r + r + r + r + r', '6r'],
    ['5a * 9b', '45ab'],
    ['25a * -4b', '-100ab'],
    ['-8b * 4a', '-32ab'],
    ['-3p * -q', '3pq'],
    ['6x * -5', '-30x'],
    ['-p * -a', 'ap'],
    ['0.2a * c * 5b', 'abc'],
    ['-8x * -y * 3z', '24xyz'],
    ['-a * -b * -1', '-ab'],
    ['5y * -3x * -z', '15xyz'],
    ['18y * 0 * -2x', '0'],
    ['-0.5ac * -8b', '4abc'],
    ['15a * 3a', '45a^2'],
    ['15a * 3b', '45ab'],
    ['-1/3x * -1/2x', 'x^2/6'],
    ['-2/3 * -3/4y', 'y/2'],
    ['1/3x * 2/3y * -3/5x', '-2x^2y / 15'],
    ['2/3x * 1/2y * 3z', 'xyz'],
    ['6a + 7a', '13a'],
    ['2c + 3c', '5c'],
    ['1096x + 4x', '1100x'],
    ['15y + y', '16y'],
    ['154x + 46x', '200x'],
    ['1.5q + 0.5q', '2q'],
    ['2ab + 8ab', '10ab'],
    ['4.5p + p', '5.5p'],
    ['c + 13c', '14c'],
    ['3a + 10a', '13a'],
    ['3a + 10b', '3a + 10b'],
    ['b + 8b', '9b'],
    ['3x + 8', '3x + 8'],
    ['2ac + 6ac', '8ac'],
    ['2ac + 8ad', '2ac + 8ad'],
    ['a + 3/2a', '5a / 2'],
    ['1/2a + 1/3b', 'frac[3a + 2b, 6]'],
    ['7/2a + 3/2a', '5a'],
    ['5a + 2b + 3a + 4b', '8a + 6b'],
    ['8a + 6 + 2a + 9', '10a + 15'],
    ['6a + 14a + 3b', '20a + 3b'],
    ['2ab + 4b + 6ab + 8b', '12b + 8ab'],
    ['3ab + 2bc + 8bc + ab', '4ab + 10bc'],
    ['6a + 5b + 6b', '6a + 11b'],
]);
