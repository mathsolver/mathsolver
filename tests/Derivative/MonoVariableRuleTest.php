<?php

use MathSolver\Derivative\MonoVariableRule;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;

it('can differentiate mono variables', function () {
    $tree = StringToTreeConverter::run('deriv(x)');
    $result = MonoVariableRule::run($tree);
    expect($result)->toEqual(new Node(1));
});

it('does not differentiate non-mono variables', function () {
    $tree = StringToTreeConverter::run('deriv(2x)');
    $result = MonoVariableRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv(2x)'));

    $tree = StringToTreeConverter::run('deriv(7)');
    $result = MonoVariableRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv(7)'));
});
