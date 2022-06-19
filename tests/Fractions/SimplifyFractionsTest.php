<?php

use MathSolver\Fractions\SimplifyFractions;
use MathSolver\Utilities\StringToTreeConverter;

it('simplifies fractions', function ($input, $output) {
    $tree = StringToTreeConverter::run($input);
    $result = SimplifyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run($output));
})->with([
    ['frac[5, 15]', 'frac[1, 3]'],
    ['frac[4, 10]', 'frac[2, 5]'],
    ['frac[8, 12]', 'frac[2, 3]'],
    ['frac[12, 20]', 'frac[3, 5]'],
    ['frac[6, 18]', 'frac[1, 3]'],
    ['frac[10, 15]', 'frac[2, 3]'],
    ['frac[8, 14]', 'frac[4, 7]'],
    ['frac[2, 16]', 'frac[1, 8]'],
    ['frac[10, 25]', 'frac[2, 5]'],
    ['frac[6, 22]', 'frac[3, 11]'],
]);

it('simplifies harder fractions', function ($input, $output) {
    $tree = StringToTreeConverter::run($input);
    $result = SimplifyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run($output));
})->with([
    ['frac[112, 16]', '7'],
    ['frac[76, 24]', 'frac[19, 6]'],
    ['frac[78, 39]', '2'],
    ['frac[115, 46]', 'frac[5, 2]'],
    ['frac[132, 48]', 'frac[11, 4]'],
    ['frac[160, 25]', 'frac[32, 5]'],
    ['frac[56, 160]', 'frac[7, 20]'],
    ['frac[64, 148]', 'frac[16, 37]'],
    ['frac[114, 228]', 'frac[1, 2]'],
    ['frac[96, 135]', 'frac[32, 45]'],
]);

it('divides by negative numbers', function () {
    $tree = StringToTreeConverter::run('frac[4, -2]');
    $result = SimplifyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-2'));
});

it('removes negative divided by negative', function () {
    $tree = StringToTreeConverter::run('frac[-1, -3]');
    $result = SimplifyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac[1, 3]'));
});

it('simplifies fractions in an already existing multiplication', function () {
    $tree = StringToTreeConverter::run('5 * frac[6, 2]');
    $result = SimplifyFractions::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5 * 3'));
});
