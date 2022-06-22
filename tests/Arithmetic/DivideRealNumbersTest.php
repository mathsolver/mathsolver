<?php

use MathSolver\Arithmetic\DivideRealNumbers;
use MathSolver\Utilities\StringToTreeConverter;

it('divides real numbers', function () {
    $tree = StringToTreeConverter::run('calc[frac[9,3]]');
    $result = DivideRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[3]'));
});

it('divides real numbers when the output is a float', function () {
    $tree = StringToTreeConverter::run('calc[frac[5,2]]');
    $result = DivideRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[2.5]'));
});

it('divides real numbers when there are unlimited decimals', function () {
    $tree = StringToTreeConverter::run('calc[frac[2,3]]');
    $result = DivideRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[0.6666666666666666]'));
});

it('wont divide letters', function () {
    $tree = StringToTreeConverter::run('calc[frac[x, 3]]');
    $result = DivideRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[frac[x, 3]]'));

    $tree = StringToTreeConverter::run('calc[frac[2, y]]');
    $result = DivideRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('calc[frac[2, y]]'));
});

it('wont divide outside the calc function', function () {
    $tree = StringToTreeConverter::run('frac[2, 5]');
    $result = DivideRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[2, 5]'));
});
