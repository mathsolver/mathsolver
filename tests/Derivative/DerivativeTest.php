<?php

use MathSolver\Runner;
use MathSolver\Utilities\StringToTreeConverter;

it('can differentiate functions', function (string $input, string $expected, string $respect = 'x') {
    $tree = $respect == 'x' ? StringToTreeConverter::run("deriv({$input})") : StringToTreeConverter::run("deriv({$input}, {$respect})");
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5x^6 - 3x^5 + 2x - 7', '30x^5 - 15x^4 + 2'],
    ['-2x^8 - 4x^4 + 7.2', '-16x^7 - 16x^3'],
    ['-frac(1, 3)x^3 - frac(1, 2)x^2 - x - 1', '-x^2 - x - 1'],
    ['1 + 3q - 3q^2 - 5q^7', '-35q^6 - 6q + 3', 'q'],
    ['(5x + 7)(4 - 3x)', '-30x - 1'],
    ['(3x + 6)^2 - 8x', '18x + 28'],
    ['5(x - 3)^2 + 5(2x - 1)', '10x - 20'],
    ['-3(x - 1)(5 - 9x) - 8(x - 7)', '54x - 50'],
    ['(3x - 1)(x^2 + 5x)', '9x^2 + 28x - 5'],
    ['(3x^3 - 1)^2', '54x^5 - 18x^2'],
    ['(5x^5 - 3)(3x - 2)', '90x^5 - 50x^4 - 9'],
    ['5 - 3(x^4 - x)(x + 1)', '-15x^4 - 12x^3 + 6x + 3'],
    ['(5t^3 - t)(3t^5 + t)', '120t^7 + 20t^3 - 18t^5 - 2t', 't'],
    ['1 - (3q^2 - 2)^2', '-36q^3 + 24q', 'q'],
]);
