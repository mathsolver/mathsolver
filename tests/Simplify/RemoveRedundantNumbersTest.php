<?php

use MathSolver\Simplify\RemoveRedundantNumbers;
use MathSolver\Utilities\StringToTreeConverter;

it('removes addition by zero', function () {
    $tree = StringToTreeConverter::run('4a + 2b + 0');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4a + 2b'));
});

it('does not leave additions', function () {
    $tree = StringToTreeConverter::run('2x + 0');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2x'));
});

it('returns a zero if no other children are left', function () {
    $tree = StringToTreeConverter::run('0 + 0');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('0'));
});

it('removes multiplication by one', function () {
    $tree = StringToTreeConverter::run('3x * 6 * 1');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3x * 6'));
});

it('does not leave multiplications', function () {
    $tree = StringToTreeConverter::run('x * 1');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x'));
});

it('returns a one if no other children are left', function () {
    $tree = StringToTreeConverter::run('1 * 1');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run(1));
});
