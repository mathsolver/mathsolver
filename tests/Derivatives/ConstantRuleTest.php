<?php

use MathSolver\Derivatives\ConstantRule;
use MathSolver\Utilities\StringToTreeConverter;

it('can differentiate constant functions', function () {
    $tree = StringToTreeConverter::run('deriv(5)');
    $result = ConstantRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run(0));
});

it('does not differentiate non-constant functions', function () {
    $tree = StringToTreeConverter::run('deriv(2x)');
    $result = ConstantRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv(2x)'));
});
