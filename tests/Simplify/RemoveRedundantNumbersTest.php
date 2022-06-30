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

it('does not remove numbers when other numbers are present', function () {
    $tree = StringToTreeConverter::run('8 + 0');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('8 + 0'));
});

it('removes multiplication by one', function () {
    $tree = StringToTreeConverter::run('x * y * 1');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x * y'));
});

it('does not leave multiplications', function () {
    $tree = StringToTreeConverter::run('x * 1');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x'));
});

it('does not return a one when other numbers are present', function () {
    $tree = StringToTreeConverter::run('7 * 1');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('7 * 1'));
});

it('removes power of one', function () {
    $tree = StringToTreeConverter::run('y^1');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('y'));
});

it('does not remove powers with a base of one', function () {
    $tree = StringToTreeConverter::run('1^x');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('1^x'));
});

it('does not remove exponents when other numbers are present', function () {
    $tree = StringToTreeConverter::run('2^1');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2^1'));
});
