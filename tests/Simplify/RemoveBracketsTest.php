<?php

use MathSolver\Simplify\RemoveBrackets;
use MathSolver\Utilities\StringToTreeConverter;

it('removes brackets when the outer presedence is lower than the inner', function () {
    $tree = StringToTreeConverter::run('4 + (5 * 3)');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4 + 5 * 3'));
});

it('removes brackets when the presedences are equal', function () {
    $tree = StringToTreeConverter::run('3(7x)');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 * 7x'));
});

it('removes brackets with only one child', function () {
    $tree = StringToTreeConverter::run('12x(4)');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('12x * 4'));
});

it('does not remove brackets with real negative numbers', function () {
    $tree = StringToTreeConverter::run('(-5)^4');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(-5)^4'));

    $tree = StringToTreeConverter::run('(-5)^3');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-5^3'));
});

it('removes brackets with letters', function () {
    $tree = StringToTreeConverter::run('(x)^y');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^y'));
});

it('removes brackets with negative exponents', function () {
    $tree = StringToTreeConverter::run('(x)^-2');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^-2'));
});

it('removes brackets when there is nothing outside', function () {
    $tree = StringToTreeConverter::run('(x + 3)');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x + 3'));
});

it('does not remove brackets in powers', function () {
    $tree = StringToTreeConverter::run('3^(x + 3)');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3^(x + 3)'));
});

it('removes brackets around exponents', function () {
    $tree = StringToTreeConverter::run('3^(4)');
    $result = RemoveBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3^4'));
});
