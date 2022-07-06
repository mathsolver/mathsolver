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
]);
