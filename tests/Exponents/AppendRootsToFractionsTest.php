<?php

use MathSolver\Exponents\AppendRootsToFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('appends square roots to fractions', function () {
    $tree = StringToTreeConverter::run('sqrt[frac[1, 2]]');
    $result = AppendRootsToFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[sqrt[1], sqrt[2]]'));
});

it('appends higher-degree roots', function () {
    $tree = StringToTreeConverter::run('root[frac[2, 5], 4]');
    $result = AppendRootsToFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[root[2, 4], root[5, 4]]'));
});
