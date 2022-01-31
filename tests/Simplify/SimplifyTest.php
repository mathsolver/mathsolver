<?php

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\StringToTreeConverter;

it('simplifies products', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree);
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['6a * 7b', '42ab'],
    ['-3b * 8a', '-24ab'],
    ['2y * -5y', '-10y^2'],
    ['r * 2p * q', '2pqr'],
    ['10u * 0 * 5y', '0'],
    ['-3e * 14d', '-42de'],
    ['8p * 6q', '48pq'],
    ['-13a * 6b', '-78ab'],
    ['2a * 3b * 4a', '24a^2b'],
    ['a * a * a', 'a^3'],
    ['x^2 * x', 'x^3'],
    ['y^3 * y * y^2', 'y^6'],
]);

it('combines like terms', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree);
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5xy + 4xy', '9xy'],
    ['15ab + 40ac', '15ab + 40ac'],
    ['4m + 12', '12 + 4m'],
    ['-2abc + 18abc', '16abc'],
    ['3def - 8de', '3def - 8de'],
    ['pq + pq', '2pq'],
    ['4x - 5y + 7x + 3y', '11x - 2y'],
    ['4x - 5y - 7x - 3y', '-3x - 8y'],
    ['-4x - 5y + 7x + 3y', '3x - 2y'],
    ['4a - 3ab + a + 3ab', '5a'],
    ['4a - 7 + 5a', '-7 + 9a'],
    ['8x + 5ab - ab - 7x', 'x + 4ab'],
    ['-p + 8p - 2qr - 9p', '-2p - 2qr'],
]);

it('simplifies with multiplications and additions', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree);
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['2x * 8y + 7x * 3y', '37xy'],
    ['3a * 5b - 9b * a', '6ab'],
    ['4x * 6 - 5 * 2x', '14x'],
    ['-2p * 3q - 3q * 8p', '-30pq'],
    ['3a * 8a + 12a * 2a', '48a^2'],
    ['2x * 3x - 3x * -5x', '21x^2'],
    ['2a * 7b + 3a * 4b', '26ab'],
    ['2a + 7b + 3a + 4b', '5a + 11b'],
    ['2 + a + 7 + b', '9 + a + b'],
    ['2a * 7b - 3a * 4b', '2ab'],
    ['2a * -7b - 3a * -4b', '-2ab'],
    ['2a * 7a + 3a * 4a', '26a^2'],
    ['4 * 3a + 8 * 2a', '28a'],
    ['4 * 3a + 8 + 2a', '8 + 14a'],
    ['4 + 3a - 8 - 2a', '-4 + a'],
]);

it('simplifies with single brackets with plus', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree);
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['3(p + 5)', '15 + 3p'],
    ['5 * 3(p + 5)', '75 + 15p'],
    ['-3(a - 2b)', '-3a + 6b'],
    ['p(3p - 6)', '-6p + 3p^2'],
    ['-4(6ab - 9c)', '-24ab + 36c'],
    ['6(a + 2b) + 2(a + 3b)', '8a + 18b'],
    ['5(2x - 8) + 4(x - 2)', '-48 + 14x'],
    ['-2(x + 6y) - 3(-2x - 3y)', '4x - 3y'],
    ['-4x - 5(7x - 2y)', '-39x + 10y'],
    ['5a - 4(2a - 8)', '32 - 3a'],
    ['a + b - (4a - 2b)', '-3a + 3b'],
    ['-8(a + 2b) - 5(3a - 2b + 2)', '-10 - 23a - 6b'],
    ['-3(x - 4y) - (x - 2y) - 13y', 'y - 4x'],
    ['-2a(a - b) + a(b - 3a) - 3ab', '-5a^2'],
]);

it('simplifies with double brackets with plus', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree);
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['(a + 7)(b + 9)', '63 + 7b + 9a + ab'],
    ['(2x + 7)(3x + 9)', '63 + 39x + 6x^2'],
    ['(q + 4)(q - 5)', '-20 - q + q^2'],
    ['(3p + 4)(2q + 5)', '20 + 8q + 15p + 6pq'],
    ['(b + 6)(b + 3)', '18 + 9b + b^2'],
    ['(4p - 5)(2q + 5)', '-25 - 10q + 20p + 8pq'],
    ['(p + 8)(p + 8)', '64 + 16p + p^2'],
    ['(x + 6)(6x - 1)', '-6 + 35x + 6x^2'],
    ['(x - 1)(x - 9)', '9 - 10x + x^2'],
    ['(a + 1)^2', '1 + 2a + a^2'],
    ['(x - 3)^2', '9 - 6x + x^2'],
    ['(b - 5)^2', '25 - 10b + b^2'],
    ['(3a + 1)(3a - 1)', '-1 + 9a^2'],
    ['(5a + 3)^2', '9 + 30a + 25a^2'],
    ['(6a - 1)^2', '1 - 12a + 36a^2'],
    ['(2a - 1)(2a + 1)', '-1 + 4a^2'],
    ['(2a - 1)(a + 2)', '-2 + 3a + 2a^2'],
    ['(a + 3b)^2 - 8b^2', 'b^2 + a^2 + 6ab'],
    ['(3a - b)(2a + 3b)', '6a^2 + 7ab - 3b^2'],
    ['(2a + 6)(a - 3) + 2(a + 9)', '2a + 2a^2'],
    ['(a - 5)^2 - 10(5 - a)', '-25 + a^2'],
    ['(a - 5b)(b - 5) - 4(2b - a)', '-a + ab + 17b -5b^2'],
    ['(2x + 3)^2 - (x - 1)(-4x + 1)', '10 + 7x + 8x^2'],
    ['8x + 3(2x - 6) - 7x', '-18 + 7x'],
    ['(5x - 6)^2 - (x + 8)^2', '-28 - 76x + 24x^2'],
    ['(a + 3)(2a + b + 1)', '3 + 7a + 3b + 2a^2 + ab'],
    ['(a - 1)(a - 2b + 3)', '-3 + 2a + 2b + a^2 - 2ab'],
    ['(x + 3)(3x + y + 6)', '18 + 15x + 3y + 3x^2 + xy'],
    ['(a + b)(2a - b - 1)', '-a + 2a^2 + ab - b -b^2'],
]);

it('removes brackets when the outside presedence is lower', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree);
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['(7x) + 3', '3 + 7x'],
    ['6(5)', '30'],
    ['8(2x) - 14x', '2x'],
    ['5 + (4 * 3)', '17'],
    ['5x(3)', '15x'],
    ['7y(3x)', '21xy'],
    ['5 + 3 + (4 + x)', '12 + x'],
    ['4 - y - (6 + 5)', '-7 - y'],
    ['5 + x - (4 + y)', '1 + x - y'],
]);

it('can calculate powers of real numbers', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree);
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5^2', '25'],
    ['7^3', '343'],
    ['2^10', '1024'],
    ['(-5)^2', '25'],
    ['(-5)^3', '-125'],
    ['(-7)^4', '2401'],
    ['-7^4', '-2401'],
]);

it('can simplify roots', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree);
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['root(18, 2)', '3root(2, 2)'],
    ['root(20, 2)', '2root(5, 2)'],
    ['2 * root(8, 2)', '4root(2,2)'],
    ['3root(9, 2)', '9'],
    ['root(512, 3)', '8'],
    ['2 * root(2 * 8, 2)', '8'],
    ['x^0.5', 'root(x, 2)'],
    ['8^1.5', '16root(2, 2)'],
]);

it('can record steps', function () {
    $tree = StringToTreeConverter::run('6a * 7b');
    $result = Simplifier::run($tree, $withSteps = true);
    $expected = StringToTreeConverter::run('42ab');

    expect($result)->toEqual([
        'tree' => $expected,
        'steps' => [
            ['name' => 'Multiply like factors', 'result' => '6*7*a*b'],
            ['name' => 'Multiply real numbers', 'result' => '42ab'],
        ],
    ]);
});
