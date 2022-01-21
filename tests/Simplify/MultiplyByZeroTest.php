<?php

use MathSolver\Simplify\MultiplyByZero;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;

it('replaces multiplications by zero with a zero', function () {
    $tree = StringToTreeConverter::run('8 * 0');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(new Node(0));
});

it('replaces when the first term is zero', function () {
    $tree = StringToTreeConverter::run('0 * 4');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(new Node(0));
});

it('replaces with zeros in any term', function () {
    $tree = StringToTreeConverter::run('x * 4 * 0');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(new Node(0));

    $tree = StringToTreeConverter::run('x * 4 * 8 * 3 * 0');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(new Node(0));

    $tree = StringToTreeConverter::run('x * 4 * 0 * 8 * 3');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(new Node(0));
});

it('does not replace without a zero', function () {
    $tree = StringToTreeConverter::run('8 * 4');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('8 * 4'));
});

it('replaces with letters', function () {
    $tree = StringToTreeConverter::run('2a * 0 * 3b');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(new Node(0));

    $tree = StringToTreeConverter::run('10u * 5y * 0');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(new Node(0));
});

it('replaces nested multiplications with zeros', function () {
    $tree = StringToTreeConverter::run('3 + x * 0');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 + 0'));
});

it('replaces nested zeros as the second term', function () {
    $tree = StringToTreeConverter::run('0 * 6 + y');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('0 + y'));
});

it('does not replace nested without a zero', function () {
    $tree = StringToTreeConverter::run('3 + 5 * 9');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 + 5 * 9'));

    $tree = StringToTreeConverter::run('3x + 4');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3x + 4'));
});

it('replaces nested with a zero in any term', function () {
    $tree = StringToTreeConverter::run('3 + 6 * 0 + 5 + 0 * 9');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 + 0 + 5 + 0'));

    $tree = StringToTreeConverter::run('3 + 4 * 9 * 3 * x * 0');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3 + 0 '));
});

it('does not replace plus', function () {
    $tree = StringToTreeConverter::run('x + x^2');
    $result = MultiplyByZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x + x^2'));
});
