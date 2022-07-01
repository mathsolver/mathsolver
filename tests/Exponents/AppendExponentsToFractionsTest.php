<?php

use MathSolver\Exponents\AppendExponentsToFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('appends exponents to fractions', function () {
    $tree = StringToTreeConverter::run('frac[1, 2]^2');
    $result = AppendExponentsToFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1^2, 2^2]'));
});

it('wraps the numerator in brackets if needed', function () {
    $tree = StringToTreeConverter::run('frac[1 + 3, 2]^2');
    $result = AppendExponentsToFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[(1 + 3)^2, 2^2]'));
});

it('wraps the denominator in brackets if needed', function () {
    $tree = StringToTreeConverter::run('frac[5, x + 3]^2');
    $result = AppendExponentsToFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[5^2, (x + 3)^2]'));
});
