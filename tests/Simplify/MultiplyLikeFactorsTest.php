<?php

use MathSolver\Simplify\MultiplyLikeFactors;
use MathSolver\Utilities\StringToTreeConverter;

it('combines simular products', function () {
    $tree = StringToTreeConverter::run('a * a');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a^2'));

    $tree = StringToTreeConverter::run('b * b * b * b');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('b^4'));
});

it('combines with more than two letters', function () {
    $tree = StringToTreeConverter::run('a * b * a * b');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('a^2 * b^2'));

    $tree = StringToTreeConverter::run('x * x * x * y * y * x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^4 * y^2'));
});

it('does not combine single letters', function () {
    $tree = StringToTreeConverter::run('x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x'));

    $tree = StringToTreeConverter::run('x * y');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('xy'));
});

it('combines when there are numbers', function () {
    $tree = StringToTreeConverter::run('4 * x * x * y');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4*x^2*y'));

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

    $tree = StringToTreeConverter::run('y^2 * y * y^3');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('y^6'));

    $tree = StringToTreeConverter::run('(y - 5)(y - 5)^2');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(y - 5)^3'));
});

it('adds brackets when it is a real negative number', function () {
    $tree = StringToTreeConverter::run('-3x * -3x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(-3)^2 * x^2'));

    $tree = StringToTreeConverter::run('-3x * -3x * -3x');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-3^3 * x^3'));
});

it('does not run when there are no double factors', function () {
    $tree = StringToTreeConverter::run('169 * 15');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('169 * 15'));

    $tree = StringToTreeConverter::run('3root(2, 2)');
    $result = MultiplyLikeFactors::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3root(2, 2)'));
});
