<?php

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\StringToTreeConverter;
use MathSolver\Utilities\TreeToStringConverter;

it('can differentiate functions', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run("deriv({$input})");
    $result = Simplifier::run($tree)['result'];
    dd(TreeToStringConverter::run($result));
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5x^6 - 3x^5 + 2x - 7', '30x^5 - 15x^4 + 2'],
    ['-2x^8 - 4x^4 + 7.2', '-16x^7 - 16x^3'],
]);
