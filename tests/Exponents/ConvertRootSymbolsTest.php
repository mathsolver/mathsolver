<?php

use MathSolver\Exponents\ConvertRootSymbols;
use MathSolver\Utilities\StringToTreeConverter;

it('converts sqrt to root2', function () {
    $tree = StringToTreeConverter::run('sqrt[25]');
    $result = ConvertRootSymbols::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('root[25, 2]'));
});

it('converts cbrt to root3', function () {
    $tree = StringToTreeConverter::run('cbrt[27]');
    $result = ConvertRootSymbols::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('root[27, 3]'));
});
