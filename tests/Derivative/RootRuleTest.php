<?php

use MathSolver\Derivative\RootRule;
use MathSolver\Utilities\StringToTreeConverter;

it('it can differentiate roots', function () {
    $tree = StringToTreeConverter::run('deriv[sqrt(x])');
    $result = RootRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1, 2] * sqrt(x)^-1'));
});

it('can differentiate cube roots', function () {
    $tree = StringToTreeConverter::run('deriv[cbrt(x])');
    $result = RootRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1, 3] * cbrt(x)^-2'));
});

it('can differentiate fourth roots', function () {
    $tree = StringToTreeConverter::run('deriv[root[x, 4]]');
    $result = RootRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1, 4] * root[x, 4]^-3'));
});

it('can differentiate nth roots', function () {
    $tree = StringToTreeConverter::run('deriv[root[x, n]]');
    $result = RootRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('n^-1 * root[x, n]^(-n + 1)'));
});

it('does not differentiate the nth-root if the nth is the respect-variable', function () {
    $tree = StringToTreeConverter::run('deriv[root[x, y], y]');
    $result = RootRule::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('deriv[root[x, y], y]'));
});
