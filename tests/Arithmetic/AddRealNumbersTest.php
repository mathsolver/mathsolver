<?php

use MathSolver\Arithmetic\AddRealNumbers;
use MathSolver\Utilities\StringToTreeConverter;

it('adds real numbers', function () {
    $tree = StringToTreeConverter::run('4 + 9');
    $result = AddRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('13'));
});

it('adds with minus', function () {
    $tree = StringToTreeConverter::run('12 - 8');
    $result = AddRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4'));
});

it('keeps letters', function () {
    $tree = StringToTreeConverter::run('6a + 5 + 2a + 9');
    $result = AddRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('6a + 2a + 14'));
});

it('adds with minus but keeps letters', function () {
    $tree = StringToTreeConverter::run('12 - 7a - 5 + 5c');
    $result = AddRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-7a + 5c + 7'));
});

it('does not add a zero', function () {
    $tree = StringToTreeConverter::run('6x + 7y');
    $result = AddRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('6x + 7y'));
});

it('adds nested real numbers', function () {
    $tree = StringToTreeConverter::run('5 * (4 + 9)');
    $result = AddRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5 * (13)'));
});

it('does not run when it cannot do anything', function () {
    $tree = StringToTreeConverter::run('5 + x');
    $result = AddRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5 + x'));
});

it('adds demical numbers', function () {
    $tree = StringToTreeConverter::run('5.5 + 0.3');
    $result = AddRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5.8'));
});
