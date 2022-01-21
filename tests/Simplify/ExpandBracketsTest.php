<?php

use MathSolver\Simplify\ExpandBrackets;
use MathSolver\Utilities\StringToTreeConverter;

it('can expand brackets', function () {
    $tree = StringToTreeConverter::run('(x + 3)^2');
    $result = ExpandBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(x + 3)(x + 3)'));
});

it('does not expand brackets when there is no numeric exponent', function () {
    $tree = StringToTreeConverter::run('(x + 3)^x');
    $result = ExpandBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(x + 3)^x'));
});

it('expands with brackets with more terms', function () {
    $tree = StringToTreeConverter::run('(x + 3 - 9)^4');
    $result = ExpandBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(x + 3 - 9)(x + 3 - 9)(x + 3 - 9)(x + 3 - 9)'));
});

it('expands with brackets with one term', function () {
    $tree = StringToTreeConverter::run('(y)^6');
    $result = ExpandBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(y)(y)(y)(y)(y)(y)'));
});

it('expands with a leading minus', function () {
    $tree = StringToTreeConverter::run('-1 * (x + 8)^2');
    $result = ExpandBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-(x + 8)(x + 8)'));
});
