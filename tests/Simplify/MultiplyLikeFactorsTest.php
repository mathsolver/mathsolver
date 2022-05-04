<?php

use MathSolver\Simplify\MultiplyLikeFactors;
use MathSolver\Utilities\StringToTreeConverter;

it('combines simular products', function () {
    $tree = StringToTreeConverter::run('a * a');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a^2'));
});

it('combines with more than two factors', function () {
    $tree = StringToTreeConverter::run('b * b * b * b');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('b^4'));
});

it('combines with more than two letters', function () {
    $tree = StringToTreeConverter::run('a * b * a * b');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a^2 * b^2'));
});

it('combines with more than two letters and factors', function () {
    $tree = StringToTreeConverter::run('x * x * x * y * y * x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^4 * y^2'));
});

it('does not combine single letters', function () {
    $tree = StringToTreeConverter::run('x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x'));
});

it('keeps single letters with more than two factors', function () {
    $tree = StringToTreeConverter::run('x * y');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('xy'));
});

it('combines when there are numbers', function () {
    $tree = StringToTreeConverter::run('4 * x * x * y');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4*x^2*y'));
});

it('combines when there is more than one number', function () {
    $tree = StringToTreeConverter::run('4 * x * 5 * y * x * x * y');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4 * 5 * x^3 * y^2'));
});

it('combines nested multiplications', function () {
    $tree = StringToTreeConverter::run('3 + x * x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 + x^2'));
});

it('does not combine plus', function () {
    $tree = StringToTreeConverter::run('a + a');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a + a'));
});

it('does not combine plus but it does combine times', function () {
    $tree = StringToTreeConverter::run('a + a * a');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a + a^2'));
});

it('combines with brackets', function () {
    $tree = StringToTreeConverter::run('(x + 2)(x + 2)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(x + 2)^2'));
});

it('combines with letters with powers', function () {
    $tree = StringToTreeConverter::run('x^2 * x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^3'));
});

it('combines multiple factors with letters', function () {
    $tree = StringToTreeConverter::run('y^2 * y * y^3');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('y^6'));
});

it('combines brackets with powers', function () {
    $tree = StringToTreeConverter::run('(y - 5)(y - 5)^2');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(y - 5)^3'));
});

it('does not combine numbers', function () {
    $tree = StringToTreeConverter::run('5 * 5');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5 * 5'));
});

it('does not combine numbers with letters', function () {
    $tree = StringToTreeConverter::run('-3x * -3x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-3 * -3 * x^2'));
});

it('keeps the order when there are no double factors', function () {
    $tree = StringToTreeConverter::run('169 * 15');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('169 * 15'));

    $tree = StringToTreeConverter::run('3sin(2, 2)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3sin(2, 2)'));
});

it('can add fractions', function () {
    $tree = StringToTreeConverter::run('x^frac(1, 2) * x^frac(3, 2)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^2'));
});

it('can add roots', function () {
    $tree = StringToTreeConverter::run('root(x, 2) * root(x, 2)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x'));
});

it('converts broken exponents into roots', function () {
    $tree = StringToTreeConverter::run('x^frac(1, 2)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('root(x, 2)'));
});

it('converts broken exponents with roots and inside powers', function () {
    $tree = StringToTreeConverter::run('x^frac(2, 3)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('root(x, 3)^2'));
});

it('converts broken exponents into a whole and broken part', function () {
    $tree = StringToTreeConverter::run('x^frac(5, 2)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^2 * root(x, 2)'));
});

it('does not append an exponent of one', function () {
    $tree = StringToTreeConverter::run('x^frac(3, 2)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x * root(x, 2)'));
});
