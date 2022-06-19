<?php

use MathSolver\Derivative\ConstantRule;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;

it('can differentiate constant functions', function () {
    $tree = StringToTreeConverter::run('deriv[5]');
    $result = ConstantRule::run($tree);
    expect($result)->toEqual(new Node(0));
});

it('differentiates constant functions with variables', function () {
    $tree = StringToTreeConverter::run('deriv[7b]');
    $result = ConstantRule::run($tree);
    expect($result)->toEqual(new Node(0));
});

it('does not differentiate non-constant functions', function () {
    $tree = StringToTreeConverter::run('deriv[2x]');
    $result = ConstantRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv[2x]'));
});

it('can apply the rule with respect to a variable', function () {
    $tree = StringToTreeConverter::run('deriv[2x, y]');
    $result = ConstantRule::run($tree);
    expect($result)->toEqual(new Node(0));

    $tree = StringToTreeConverter::run('deriv[7y, y]');
    $result = ConstantRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv[7y, y]'));
});
