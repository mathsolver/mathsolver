<?php

use MathSolver\Derivative\SumRule;
use MathSolver\Utilities\StringToTreeConverter;

it('can apply the sum rule', function () {
    $tree = StringToTreeConverter::run('deriv(x^2 + 2x)');
    $result = SumRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv(x^2) + deriv(2x)'));
});

it('can apply the rule when the deriv function is in a sum', function () {
    $tree = StringToTreeConverter::run('3 + deriv(3x + 7)');
    $result = SumRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 + deriv(3x) + deriv(7)'));
});

it('can apply the rule with respect to a variable', function () {
    $tree = StringToTreeConverter::run('deriv(y^2 + 2y, y)');
    $result = SumRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv(y^2, y) + deriv(2y, y)'));
});
