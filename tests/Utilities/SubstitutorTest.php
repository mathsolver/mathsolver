<?php

use MathSolver\Utilities\StringToTreeConverter;
use MathSolver\Utilities\Substitutor;

it('can substitute a value', function () {
    $tree = StringToTreeConverter::run('5 + x');
    $result = Substitutor::run($tree, ['x' => '6']);
    expect($result)->toEqual(StringToTreeConverter::run('5 + (6)'));
});

it('can substitute a value with more nodes', function () {
    $tree = StringToTreeConverter::run('5 + x');
    $result = Substitutor::run($tree, ['x' => '5 * 6']);
    expect($result)->toEqual(StringToTreeConverter::run('5 + (5 * 6)'));
});

it('replaces all encounters', function () {
    $tree = StringToTreeConverter::run('5 + x * x');
    $result = Substitutor::run($tree, ['x' => '2y']);
    expect($result)->toEqual(StringToTreeConverter::run('5 + (2y)(2y)'));
});

it('leaves brackets', function () {
    $tree = StringToTreeConverter::run('7(x + 3');
    $result = Substitutor::run($tree, ['x' => '4 + 3']);
    expect($result)->toEqual(StringToTreeConverter::run('7((4 + 3) + 3)'));
});

it('can substitute multiple values', function () {
    $tree = StringToTreeConverter::run('x + y');
    $result = Substitutor::run($tree, ['x' => '4', 'y' => '5']);
    expect($result)->toEqual(StringToTreeConverter::run('(4) + (5)'));
});
