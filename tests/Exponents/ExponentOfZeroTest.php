<?php

use MathSolver\Exponents\ExponentOfZero;
use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;

it('replaces powers with an exponent of zero by one', function () {
    $tree = StringToTreeConverter::run('8^0');
    $result = ExponentOfZero::run($tree);
    expect($result)->toEqual(new Node(1));
});

it('does not replace powers without an exponent of zero', function () {
    // Positive exponent
    $tree = StringToTreeConverter::run('8^2');
    $result = ExponentOfZero::run($tree);
    expect($result)->toEqual($tree);

    // Negative exponent
    $tree = StringToTreeConverter::run('8^-5');
    $result = ExponentOfZero::run($tree);
    expect($result)->toEqual($tree);
});

it('replaces nested powers', function () {
    $tree = StringToTreeConverter::run('5^0 + 8');
    $result = ExponentOfZero::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('1 + 8'));
});

it('does not replace nested powers if the exponent is not zero', function () {
    // Positive exponent
    $tree = StringToTreeConverter::run('5^7 + 8');
    $result = ExponentOfZero::run($tree);
    expect($result)->toEqual($tree);

    // Negative exponent
    $tree = StringToTreeConverter::run('5^-1 + 8');
    $result = ExponentOfZero::run($tree);
    expect($result)->toEqual($tree);
});

it('works with plus', function () {
    $tree = StringToTreeConverter::run('y + y^2');
    $result = ExponentOfZero::run($tree);
    expect($result)->toEqual($tree);
});
