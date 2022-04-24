<?php

use MathSolver\Derivatives\PowerRule;
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
