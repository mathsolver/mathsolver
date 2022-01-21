<?php

use MathSolver\Simplify\MultiplyRealNumbers;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;

it('can multiply real numbers', function () {
    $tree = StringToTreeConverter::run('3 * 5');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(new Node(15));
});

it('can multiply with minus', function () {
    $tree = StringToTreeConverter::run('-3 * -2b');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('6b'));

    $tree = StringToTreeConverter::run('7 - 5a * 9b');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('7 - 45ab'));

    $tree = StringToTreeConverter::run('-3a + -3 * -2b');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-3a + 6b'));
});

it('can multiply real numbers including zeros', function () {
    $tree = StringToTreeConverter::run('3 * 0');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(new Node(0));

    $tree = StringToTreeConverter::run('0');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(new Node(0));

    $tree = new Node(0);
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(new Node(0));
});

it('can multiply with more than two terms', function () {
    $tree = StringToTreeConverter::run('2 * 3 * 5 * 2');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(new Node(60));
});

it('does not multiply letters', function () {
    $tree = StringToTreeConverter::run('x * y * z');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual($tree);
});

it('does multiply numbers but keeps letters', function () {
    $tree = StringToTreeConverter::run('2 * x * 5 * y');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('10 * x * y'));
});

it('can multiply nested real numbers', function () {
    $tree = StringToTreeConverter::run('4 + 3 * 5');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4 + 15'));

    $tree = StringToTreeConverter::run('3 * 6 + 8');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('18 + 8'));

    $tree = StringToTreeConverter::run('4 + 3 * 2 * 6');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4 + 36'));
});

it('can multiply nested with more than two terms', function () {
    $tree = StringToTreeConverter::run('x + 4 * 3 * 9 + y');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x + 108 + y'));
});

it('does not multiply nested letters', function () {
    $tree = StringToTreeConverter::run('4 + x * y * z');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual($tree);
});

it('does multiply nested numbers but keeps letters', function () {
    $tree = StringToTreeConverter::run('5 + 6 * x * 5 + 9');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5 + 30 * x + 9'));
});

it('sorts letters', function () {
    $tree = StringToTreeConverter::run('3c * 8a');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('24ac'));
});

it('does not multiply with an coefficient of one', function () {
    $tree = StringToTreeConverter::run('ab + ab');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('ab+ab'));
});

it('does multiply with powers', function () {
    $tree = StringToTreeConverter::run('x^3 * x * x^2');
    $result = MultiplyRealNumbers::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x^3 * x^2 * x'));
});
