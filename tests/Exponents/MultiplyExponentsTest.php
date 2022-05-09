<?php

use MathSolver\Exponents\MultiplyExponents;
use MathSolver\Utilities\StringToTreeConverter;

it('multiplies exponents', function () {
    $tree = StringToTreeConverter::run('(x^2)^-1');
    $result = MultiplyExponents::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^-2'));
});

it('multiplies exponents with letters', function () {
    $tree = StringToTreeConverter::run('(x^a)^b');
    $result = MultiplyExponents::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^(ab)'));
});

it('multiplies with letters and numbers', function () {
    $tree = StringToTreeConverter::run('(x^a)^3');
    $result = MultiplyExponents::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^(a*3)'));
});

it('does not multiply exponents without brackets', function () {
    $tree = StringToTreeConverter::run('x^a^b');
    $result = MultiplyExponents::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^a^b'));
});
