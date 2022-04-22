<?php

use MathSolver\Derivatives\Differentiator;
use MathSolver\Utilities\StringToTreeConverter;

it('can differentiate with the power rule', function (string $input, string $output) {
    $tree = StringToTreeConverter::run("deriv({$input})");
    $result = Differentiator::run($tree);
    $expected = StringToTreeConverter::run($output);
    expect($result)->toEqual($expected);
})->with([
    ['x^2', '2x'],
    ['x^5', '5x^4'],
    ['x^18', '18x^17'],
    ['x^-2', '-2x^-3'],
]);
