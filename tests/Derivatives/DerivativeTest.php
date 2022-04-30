<?php

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\StringToTreeConverter;

it('can differentiate functions', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run("deriv({$input})");
    $result = Simplifier::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5x^6 - 3x^5 + 2x - 7', '30x^5 - 15x^4 + 2'],
    ['-2x^8 - 4x^4 + 7.2', '-16x^7 - 16x^3'],
    ['-frac(1, 3)x^3 - frac(1, 2)x^2 - x - 1', '-x^2 - x - 1'],
]);
