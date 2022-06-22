<?php

use MathSolver\Functions\RoundNumbers;
use MathSolver\Utilities\StringToTreeConverter;

it('can round numbers', function () {
    $tree = StringToTreeConverter::run('calc[0.125, 2]');
    $result = RoundNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('0.13'));
});

it('does not round letters', function () {
    $tree = StringToTreeConverter::run('calc[x, 2]');
    $result = RoundNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[x, 2]'));
});

it('does not round expressions', function () {
    $tree = StringToTreeConverter::run('calc[2 + 5, 2]');
    $result = RoundNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[2 + 5, 2]'));
});

it('only rounds when an precision is given', function () {
    $tree = StringToTreeConverter::run('calc[8.24, x]');
    $result = RoundNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[8.24, x]'));
});

it('does not round when the precision is a float', function () {
    $tree = StringToTreeConverter::run('calc[8.24, 5.5]');
    $result = RoundNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[8.24, 5.5]'));
});

it('will return the number if no rounding is given', function () {
    $tree = StringToTreeConverter::run('calc[8]');
    $result = RoundNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('8'));
});

it('wont return the value if it is a letter', function () {
    $tree = StringToTreeConverter::run('calc[y]');
    $result = RoundNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[y]'));
});

it('wont return the value if is is an expression', function () {
    $tree = StringToTreeConverter::run('calc[8 - 3]');
    $result = RoundNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[8 - 3]'));
});
