<?php

use MathSolver\Derivative\PowerRule;
use MathSolver\Utilities\StringToTreeConverter;

it('can apply the power rule', function () {
    $tree = StringToTreeConverter::run('deriv(x^2)');
    $result = PowerRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2x^1'));
});

it('can apply the power rule with higher exponents', function () {
    $tree = StringToTreeConverter::run('deriv(x^5)');
    $result = PowerRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5x^4'));
});

it('can apply the power rule with negative exponents', function () {
    $tree = StringToTreeConverter::run('deriv(x^-2)');
    $result = PowerRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-2x^-3'));
});

it('can apply the power rule with other numeric values', function () {
    $tree = StringToTreeConverter::run('deriv(x^π)');
    $result = PowerRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('π * x^(π-1)'));
});

it('can apply the rule with respect to a variable', function () {
    $tree = StringToTreeConverter::run('deriv(z^3, z)');
    $result = PowerRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3z^2'));
});

it('can apply the rule in a product', function () {
    $tree = StringToTreeConverter::run('2 * deriv(x^2)');
    $result = PowerRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2 * 2 * x^1'));
});

it('does not differentiate with the respect-letter in the exponent', function () {
    $tree = StringToTreeConverter::run('deriv(x^x)');
    $result = PowerRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv(x^x)'));

    $tree = StringToTreeConverter::run('deriv(x^(2x))');
    $result = PowerRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv(x^(2x))'));
});
