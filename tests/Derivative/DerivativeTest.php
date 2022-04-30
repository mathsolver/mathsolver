<?php

use MathSolver\Runner;
use MathSolver\Utilities\StringToTreeConverter;

it('can differentiate functions', function (string $input, string $expected, string $respectTo = 'x') {
    $tree = $respectTo == 'x' ? StringToTreeConverter::run("deriv({$input})") : StringToTreeConverter::run("deriv({$input}, {$respectTo})");
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5x^6 - 3x^5 + 2x - 7', '30x^5 - 15x^4 + 2'],
    ['-2x^8 - 4x^4 + 7.2', '-16x^7 - 16x^3'],
    ['-frac(1, 3)x^3 - frac(1, 2)x^2 - x - 1', '-x^2 - x - 1'],
    ['1 + 3q - 3q^2 - 5q^7', '-35q^6 - 6q + 3', 'q'],
]);
