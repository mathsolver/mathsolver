<?php

use MathSolver\Simplify\RemoveRedundantNumbers;
use MathSolver\Utilities\StringToTreeConverter;

it('removes redundant numbers', function () {
    $tree = StringToTreeConverter::run('4a + 2b + 0');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4a + 2b'));
});

it('does not leave additions', function () {
    $tree = StringToTreeConverter::run('2x + 0');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2x'));
});

it('returns a zero is no other children are left', function () {
    $tree = StringToTreeConverter::run('0 + 0');
    $result = RemoveRedundantNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('0'));
});
