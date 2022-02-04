<?php

use MathSolver\Simplify\Simplifier;
use MathSolver\Utilities\StringToTreeConverter;

it('simplifies products', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
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
    $result = Simplifier::run($tree)['tree'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5xy + 4xy', '9xy'],
    ['15ab + 40ac', '15ab + 40ac'],
    ['4m + 12', '4m + 12'],
    ['-2abc + 18abc', '16abc'],
    ['3def - 8de', '3def - 8de'],
    ['pq + pq', '2pq'],
    ['4x - 5y + 7x + 3y', '11x - 2y'],
    ['4x - 5y - 7x - 3y', '-3x - 8y'],
    ['-4x - 5y + 7x + 3y', '3x - 2y'],
    ['4a - 3ab + a + 3ab', '5a'],
    ['4a - 7 + 5a', '9a - 7'],
    ['8x + 5ab - ab - 7x', 'x + 4ab'],
    ['-p + 8p - 2qr - 9p', '-2p - 2qr'],
]);

it('simplifies with multiplications and additions', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
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
    ['2 + a + 7 + b', 'a + b + 9'],
    ['2a * 7b - 3a * 4b', '2ab'],
    ['2a * -7b - 3a * -4b', '-2ab'],
    ['2a * 7a + 3a * 4a', '26a^2'],
    ['4 * 3a + 8 * 2a', '28a'],
    ['4 * 3a + 8 + 2a', '14a + 8'],
    ['4 + 3a - 8 - 2a', 'a - 4'],
]);

it('simplifies with single brackets with plus', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['3(p + 5)', '3p + 15'],
    ['5 * 3(p + 5)', '15p + 75'],
    ['-3(a - 2b)', '-3a + 6b'],
    ['p(3p - 6)', '3p^2 - 6p'],
    ['-4(6ab - 9c)', '36c - 24ab'],
    ['6(a + 2b) + 2(a + 3b)', '8a + 18b'],
    ['5(2x - 8) + 4(x - 2)', '14x - 48'],
    ['-2(x + 6y) - 3(-2x - 3y)', '4x - 3y'],
    ['-4x - 5(7x - 2y)', '-39x + 10y'],
    ['5a - 4(2a - 8)', '-3a + 32'],
    ['a + b - (4a - 2b)', '-3a + 3b'],
    ['-8(a + 2b) - 5(3a - 2b + 2)', '-23a - 6b - 10'],
    ['-3(x - 4y) - (x - 2y) - 13y', '-4x + y'],
    ['-2a(a - b) + a(b - 3a) - 3ab', '-5a^2'],
]);

it('simplifies with double brackets with plus', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['(a + 7)(b + 9)', '9a + 7b + ab + 63'],
    ['(2x + 7)(3x + 9)', '6x^2 + 39x + 63'],
    ['(q + 4)(q - 5)', 'q^2 - q - 20'],
    ['(3p + 4)(2q + 5)', '15p + 8q + 6pq + 20'],
    ['(b + 6)(b + 3)', 'b^2 + 9b + 18'],
    ['(4p - 5)(2q + 5)', '20p - 10q + 8pq - 25'],
    ['(p + 8)(p + 8)', 'p^2 + 16p + 64'],
    ['(x + 6)(6x - 1)', '6x^2 + 35x - 6'],
    ['(x - 1)(x - 9)', 'x^2 - 10x + 9'],
    ['(a + 1)^2', 'a^2 + 2a + 1'],
    ['(x - 3)^2', 'x^2 - 6x + 9'],
    ['(b - 5)^2', 'b^2 - 10b + 25'],
    ['(3a + 1)(3a - 1)', '9a^2 - 1'],
    ['(5a + 3)^2', '25a^2 + 30a + 9'],
    ['(6a - 1)^2', '36a^2 - 12a + 1'],
    ['(2a - 1)(2a + 1)', '4a^2 - 1'],
    ['(2a - 1)(a + 2)', '2a^2 + 3a - 2'],
    ['(a + 3b)^2 - 8b^2', 'b^2 + a^2 + 6ab'],
    ['(3a - b)(2a + 3b)', '6a^2 - 3b^2 + 7ab'],
    ['(2a + 6)(a - 3) + 2(a + 9)', '2a^2 + 2a'],
    ['(a - 5)^2 - 10(5 - a)', 'a^2 - 25'],
    ['(a - 5b)(b - 5) - 4(2b - a)', '-5b^2 - a + 17b + ab'],
    ['(2x + 3)^2 - (x - 1)(-4x + 1)', '8x^2 + 7x + 10'],
    ['8x + 3(2x - 6) - 7x', '7x - 18'],
    ['(5x - 6)^2 - (x + 8)^2', '24x^2 - 76x - 28'],
    ['(a + 3)(2a + b + 1)', '2a^2 + 7a + 3b + ab + 3'],
    ['(a - 1)(a - 2b + 3)', 'a^2 + 2a + 2b - 2ab - 3'],
    ['(x + 3)(3x + y + 6)', '3x^2 + 15x + 3y + xy + 18'],
    ['(a + b)(2a - b - 1)', '2a^2 - b^2 - a - b + ab'],
]);

it('removes brackets when the outside presedence is lower', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['(7x) + 3', '7x + 3'],
    ['6(5)', '30'],
    ['8(2x) - 14x', '2x'],
    ['5 + (4 * 3)', '17'],
    ['5x(3)', '15x'],
    ['7y(3x)', '21xy'],
    ['5 + 3 + (4 + x)', 'x + 12'],
    ['4 - y - (6 + 5)', '-y - 7'],
    ['5 + x - (4 + y)', 'x - y + 1'],
]);

it('can calculate powers of real numbers', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
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
    $result = Simplifier::run($tree)['tree'];
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

it('can calculate with fractions', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['frac(12, 8)', 'frac(3, 2)'],
    ['frac(22, 14)', 'frac(11, 7)'],
    ['frac(36, 27)', 'frac(4, 3)'],
    ['frac(42, 72)', 'frac(7, 12)'],
    ['5frac(4,2)', '10'],
    ['frac(2, 8) * frac(1, 4)', 'frac(1, 16)'],
    ['5frac(1, 3)', 'frac(5, 3)'],
    ['frac(3, 5) + frac(1, 5)', 'frac(4, 5)'],
    ['2frac(2, 5) + frac(3, 5)', 'frac(7, 5)'],
    ['1 - frac(2, 5)', 'frac(3, 5)'],
]);

it('can make conversions between fractions and negative exponents', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['4^-2', 'frac(1, 16)'],
    ['3^-1', 'frac(1, 3)'],
]);

it('removes redundant numbers', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Simplifier::run($tree)['tree'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['4a + 0', '4a'],
    ['5x - 0', '5x'],
    ['0 + 2y', '2y'],
]);

it('records steps', function () {
    $tree = StringToTreeConverter::run('6a * 7b + 5ab');
    $result = Simplifier::run($tree);

    expect($result)->toEqual([
        'tree' => StringToTreeConverter::run('47ab'),
        'steps' => [
            ['type' => 'simplify', 'name' => 'Multiply real numbers', 'result' => '42ab+5ab'],
            ['type' => 'simplify', 'name' => 'Add like terms', 'result' => '47ab'],
        ],
    ]);
});
