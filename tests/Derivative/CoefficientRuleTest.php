<?php

use MathSolver\Derivative\CoefficientRule;
use MathSolver\Utilities\StringToTreeConverter;

it('can apply the coefficient rule', function () {
    $tree = StringToTreeConverter::run('deriv[5x]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5deriv[x]'));
});

it('can apply the coefficient rule with constant variables', function () {
    $tree = StringToTreeConverter::run('deriv[ax]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a * deriv[x]'));
});

it('can apply the coefficient rule with multiple constants', function () {
    $tree = StringToTreeConverter::run('deriv[3ax]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a * 3 * deriv[x]'));
});

it('can leave multiple factors in the deriv function', function () {
    $tree = StringToTreeConverter::run('deriv[2 * x * x^3]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2 * deriv[x * x^3]'));
});

it('works if the deriv function is in a product', function () {
    $tree = StringToTreeConverter::run('3 * deriv[2x]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 * 2 * deriv[x]'));
});

it('only works in products', function () {
    $tree = StringToTreeConverter::run('deriv[x^3]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv[x^3]'));
});

it('doesnt always create a times symbol', function () {
    $tree = StringToTreeConverter::run('deriv[x * x^2]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv[x * x^2]'));
});

it('can apply the rule with respect to a variable', function () {
    $tree = StringToTreeConverter::run('deriv[7y^2, y]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('7 * deriv[y^2, y]'));
});

it('adds brackets if it is in a power', function () {
    $tree = StringToTreeConverter::run('2^deriv[3x]');
    $result = CoefficientRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2^(3deriv[x])'));
});
