<?php

use MathSolver\Simplify\AddLikeTerms;
use MathSolver\Utilities\StringToTreeConverter;

it('combines like terms', function () {
    $tree = StringToTreeConverter::run('7x + 5x');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('12x'));
});

it('combines without a number', function () {
    $tree = StringToTreeConverter::run('y + y');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2y'));
});

it('combines more than two times', function () {
    $tree = StringToTreeConverter::run('7x + 5x + 3x + 9x + 6x');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('30x'));
});

it('combines with more than one term', function () {
    $tree = StringToTreeConverter::run('7x + 5y + 3x + 9y + 6x');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('16x + 14y'));
});

it('combines multiple letters in one term', function () {
    $tree = StringToTreeConverter::run('9xy + 3xy');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('12xy'));
});

it('combines multiple letters in one term with more than one term', function () {
    $tree = StringToTreeConverter::run('8x + 7xy + 5xy + 6xy + 6x');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('14x + 18xy'));
});

it('combines with minus', function () {
    $tree = StringToTreeConverter::run('-5x + 8x');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3x'));
});

it('combines with minus and multiple letters in one term', function () {
    $tree = StringToTreeConverter::run('8x + 5ab - ab - 7x');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x + 4ab'));
});

it('filters out zeros', function () {
    $tree = StringToTreeConverter::run('8x - 4y + 4y');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('8x'));
});

it('removes leading ones', function () {
    $tree = StringToTreeConverter::run('10c - 9c');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('c'));
});

it('combines with powers', function () {
    $tree = StringToTreeConverter::run('4x^2 + 4x^2');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('8 * x^2'));
});

it('combines with powers without coefficients', function () {
    $tree = StringToTreeConverter::run('y^3 + y^3');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2 * y^3'));
});

it('does not run when there are multiple numbers in a product', function () {
    $tree = StringToTreeConverter::run('3p + 3*5');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3p + 3*5'));
});

it('works with fractions', function () {
    $tree = StringToTreeConverter::run('frac(3, 2)x + frac(1, 2)x');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2x'));
});

it('works by subtracting fractions', function () {
    $tree = StringToTreeConverter::run('frac(1, 2)y + frac(-1, 4)y');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(1, 4)y'));
});

it('does not run if there are multiple numbers', function () {
    $tree = StringToTreeConverter::run('frac(1, 2)y - frac(1, 4)y');
    $result = AddLikeTerms::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('frac(1, 2)y - frac(1, 4)y'));
});
