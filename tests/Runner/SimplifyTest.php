<?php

use MathSolver\Runner;
use MathSolver\Utilities\StringToTreeConverter;

it('simplifies products and adds like terms', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['p + p + p + p', '4p'],
    ['x + x', '2x'],
    ['a + a + a + a + a + a + a + a', '8a'],
    ['r + r + r + r + r + r', '6r'],
    ['5a * 9b', '45ab'],
    ['25a * -4b', '-100ab'],
    ['-8b * 4a', '-32ab'],
    ['-3p * -q', '3pq'],
    ['6x * -5', '-30x'],
    ['-p * -a', 'ap'],
    ['0.2a * c * 5b', 'abc'],
    ['-8x * -y * 3z', '24xyz'],
    ['-a * -b * -1', '-ab'],
    ['5y * -3x * -z', '15xyz'],
    ['18y * 0 * -2x', '0'],
    ['-0.5ac * -8b', '4abc'],
    ['15a * 3a', '45a^2'],
    ['15a * 3b', '45ab'],
    ['-1/3x * -1/2x', 'x^2/6'],
    ['-2/3 * -3/4y', 'y/2'],
    ['1/3x * 2/3y * -3/5x', '-2x^2y / 15'],
    ['2/3x * 1/2y * 3z', 'xyz'],
    ['6a + 7a', '13a'],
    ['2c + 3c', '5c'],
    ['1096x + 4x', '1100x'],
    ['15y + y', '16y'],
    ['154x + 46x', '200x'],
    ['1.5q + 0.5q', '2q'],
    ['2ab + 8ab', '10ab'],
    ['4.5p + p', '5.5p'],
    ['c + 13c', '14c'],
    ['3a + 10a', '13a'],
    ['3a + 10b', '3a + 10b'],
    ['b + 8b', '9b'],
    ['3x + 8', '3x + 8'],
    ['2ac + 6ac', '8ac'],
    ['2ac + 8ad', '2ac + 8ad'],
    ['a + 3/2a', '5a / 2'],
    ['1/2a + 1/3b', 'frac[3a + 2b, 6]'],
    ['7/2a + 3/2a', '5a'],
    ['5a + 2b + 3a + 4b', '8a + 6b'],
    ['8a + 6 + 2a + 9', '10a + 15'],
    ['6a + 14a + 3b', '20a + 3b'],
    ['2ab + 4b + 6ab + 8b', '12b + 8ab'],
    ['3ab + 2bc + 8bc + ab', '4ab + 10bc'],
    ['6a + 5b + 6b', '6a + 11b'],
    ['6a + 8a + 5a', '19a'],
    ['6a * 8a', '48a^2'],
    ['6a + 8a + a', '15a'],
    ['6a * 8b', '48ab'],
    ['3x + 4x + x + 2y', '8x + 2y'],
    ['-3x * -4y * -x', '-12x^2y'],
    ['3x * -2y', '-6xy'],
    ['3x + y + 2y + y', '3x + 4y'],
    ['p + p + q', '2p + q'],
    ['p + 5 + q + 8', 'p + q + 13'],
    ['-5/3p * -2/5p', '2p^2 / 3'],
    ['6p * -2/3 * 3q', '-12pq'],
    ['5a + 3b + 7/3c + 6a + 7b + 17/2c', 'frac[66a + 60b + 65c, 6]'],
    ['3ab + 5bc + 7ac + 8ab + 9ac + 10bc', '11ab + 15bc + 16ac'],
    ['7pq + 8p + 9q + 6pq + pq + p + q', '9p + 10q + 14pq'],
    ['3ab * -5 * -5/2ce * 8d', '300abcde'],
    ['12abc * 5 def * 0 * 30gh', '0'],
    ['12abc * 5bd * 2 * 30ae', '3600a^2b^2cde'],
    ['3a - 5a', '-2a'],
    ['-3a - 5a', '-8a'],
    ['-3a + 5a', '2a'],
    ['6b + -2b', '4b'],
    ['-6b +- 2b', '-8b'],
    ['-6b - -2b', '-4b'],
    ['5x - 4x', 'x'],
    ['-5x + 5x', '0'],
    ['4x - 5x', '-x'],
    ['6p - 12p', '-6p'],
    ['-2a - 8a', '-10a'],
    ['0.6x + 1.4x', '2x'],
    ['5ab - 12ab', '-7ab'],
    ['-3pq - 4pr', '-3pq - 4pr'],
    ['-12ac + ac', '-11ac'],
    ['6a - 7a', '-a'],
    ['3bc - 2bc', 'bc'],
    ['-5ac + 5bc', '-5ac + 5bc'],
    ['2x - 3x - 4y', '-x - 4y'],
    ['2x - 3y - 4y', '2x - 7y'],
    ['-3a - 4a + 2b', '-7a + 2b'],
    ['7p - 4q - 3q', '7p - 7q'],
    ['-3t - 4t - 5t', '-12t'],
    ['2a - 3b + 5a - 2b', '7a - 5b'],
    ['-3a - b + 2b - a', '-4a + b'],
    ['3a + 2b + 8a - 2b', '11a'],
    ['5x + 4y - x - 5y', '4x - y'],
    ['-3x + 8y - 8y + 3x', '0'],
    ['x - y + x + y', '2x'],
    ['2x - 3 + 5x - 2', '7x - 5'],
    ['-x + y + x - y', '0'],
    ['13/4x - 1 + 8 - 1/2x', 'frac[11x + 28, 4]'],
    ['-a - b - a + b', '-2a'],
    ['8a + 4 - 8a + 2', '6'],
    ['-2/3a - ab - 3ab + 1/5a', 'frac[-7a - 60ab, 15]'],
    ['2 * 3a + 6 * 5a', '36a'],
    ['3 * 4n - 2n * 7', '-2n'],
    ['5p * 3q - 2 * 4pq', '7pq'],
    ['3x * 5y + 1/2x * 4y', '17xy'],
    ['4a * 5b - 4b * 6a', '-4ab'],
    ['20xy + 6x * 1/3y', '22xy'],
    ['4x * 3y + 5x * -4y', '-8xy'],
    ['4x + 3y + 5x - 4y', '9x - y'],
    ['6x * -2y - 3y * -4x', '0'],
    ['-4 * 3x - 4x + 3y', '-16x + 3y'],
    ['5 * 2b + 10 * 3b', '40b'],
    ['-5 * 2b + 3 * b', '-7b'],
    ['8 * -2b - 5 * 3b', '-31b'],
    ['5a * 2b - 2a * b', '8ab'],
    ['5a * 3b - 2a * c', '15ab - 2ac'],
    ['-8a * 3 - 5 * -3a', '-9a'],
    ['3 * 2x + 4 * 2x', '14x'],
    ['5 * 3y - 8 * 2y', '-y'],
    ['5x * 2y - 15x * y', '-5xy'],
    ['3x * 2z - 5x * 2y', '6xz - 10xy'],
    ['-4 * 2x - 8 * 6', '-8x - 48'],
    ['-4 * 2x - 8x * -3', '16x'],
    ['2 * 3x + 5 * 2x', '16x'],
    ['2 + 3x + 5 + 2x', '5x + 7'],
    ['2 * 3x + 5 + 2x', '8x + 5'],
    ['-3 * 2x + 5x - 2x', '-3x'],
    ['-3 + 2x + 5 - 2x', '2'],
    ['-3 * 2x - 5 * 2x', '-16x'],
    ['3 * 4a + 5 * 2a', '22a'],
    ['-3a * 4b + 2a * -3b', '-18ab'],
    ['-3a + 4b + 2a + 3b', '-a + 7b'],
    ['-3a * 3b + 2a * 3b', '-3ab'],
    ['-3a - 3b + 2a - 3b', '-a - 6b'],
    ['-3a * -3b + 2a * -3b', '3ab'],
    ['5x * 3y - 3x + 2y', '-3x + 2y + 15xy'],
    ['5 * 3y - 3x * -2y', '15y + 6xy'],
    ['5x + 3y - 3x - 2y', '2x + y'],
    ['6a * -2b - 2b * -a + 5a * -2b', '-20ab'],
    ['3a - 2b - 2b * -a + 5a * -2b', '3a - 2b - 8ab'],
    ['3a - 2b - 2b - a + 5b - 2b', '2a - b'],
]);

it('expands brackets', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['5(a + c)', '5a + 5c'],
    ['8(2a + b)', '16a + 8b'],
    ['a(3b + c)', '3ab + ac'],
    ['x(2y + 3)', '3x + 2xy'],
    ['3/2(4x + 2y)', '6x + 3y'],
    ['2p(q + 1)', '2p + 2pq'],
    ['6a(3b + 1/2c)', '3ac + 18ab'],
    ['1/2p(4q + 8s)', '2pq + 4ps'],
    ['5a(2c + 3/2)', 'frac[15a + 20ac, 2]'],
    ['1/3c(3/4a + 6)', 'frac[8c + ac, 4]'],
    ['7ab(c + 3/14d)', 'frac[3abd + 14abc, 2]'],
    ['2/3pq(3r + 3/2s)', 'pqs + 2pqr'],
    ['4(a + 3b) + 2a', '6a + 12b'],
    ['5(x + 2y) + 3y', '5x + 13y'],
    ['5(a + 2b) + 3(3a + b)', '14a + 13b'],
    ['4(2x + 5y) + 5(x + 5y)', '13x + 45y'],
    ['y = 8(x + 6)', 'y = 8x + 48'],
    ['T = 4(2a + 3)', 'T = 8a + 12'],
    ['y = 5(x + 1) + 3(2x + 6)', 'y = 11x + 23'],
    ['A = 5(2b + 3) + 3(4b + 2)', 'A = 22b + 21'],
    ['-4(x + 2y)', '-4x - 8y'],
    ['4(x - 2y)', '4x - 8y'],
    ['-4(x - 3)', '-4x + 12'],
    ['-4(2x + 8)', '-8x - 32'],
    ['-(2x - 3)', '-2x + 3'],
    ['-(2x + 3)', '-2x - 3'],
    ['-3a(2b + c)', '-6ab - 3ac'],
    ['5a(3b - c)', '15ab - 5ac'],
    ['-2p(3q - 1)', '2p - 6pq'],
    ['-1/3a(1/2b - 6c)', 'frac[-ab + 12ac, 6]'],
    ['-1/4(-4q - 1/3r)', ' frac[12q + r, 12]'],
    ['2/3b(1/5c - 3/2d)', 'frac[2bc - 15bd, 15]'],
    ['3(a + 2b) - 6a', '-3a + 6b'],
    ['-5(a - 2b) + 6a', 'a + 10b'],
    ['5(a - 2b) + 3(2a - b)', '11a - 13b'],
    ['8(a - b) - 5(a - 3)', '3a - 8b + 15'],
    ['2a - (5 + 2a)', '-5'],
    ['-3a(b - 1) - 3a', '-3ab'],
    ['7(x + y) - 2(3x - y) + 5x', '6x + 9y'],
    ['3p - p(q + 3) + q(2 - p)', '2q - 2pq'],
    ['-3(a - 6b) - 2ab - a(b - 4)', 'a + 18b - 3ab'],
    ['8y - 2x(y + 7) - y(x - 8)', '-14x + 16y - 3xy'],
    ['-3x - 2y + x(y - 5)', '-8x - 2y + xy'],
    ['5p(q + 3) - 2p * -3q', '15p + 11pq'],
    ['2x(5y - 2) - 5x * 2y', '-4x'],
    ['3a * 7b + 2a(3 - 5b) + b(1 - a)', '6a + b + 10ab'],
    ['2p(q - 1) - (1 - 2p)', '2pq - 1'],
    ['-3p * -2q - q(6p + 1)', '-q'],
]);

it('simplifies products and sums with powers', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['b^5 * b^8', 'b^13'],
    ['x * x^4', 'x^5'],
    ['x^3 * x^5 * x', 'x^9'],
    ['x^5 * x * x * x^2', 'x^9'],
    ['p^8 * p^9', 'p^17'],
    ['p * p * p', 'p^3'],
    ['2a^5 * 3a^7', '6a^12'],
    ['8a^6 * -3a', '-24a^7'],
    ['4m^6 * -2m^7', '-8m^13'],
    ['-y^3 * 2y^9', '-2y^12'],
    ['3p^4 * 2p^12', '6p^16'],
    ['q^6 * 3q', '3q^7'],
    ['5x^3 * -2x^4 * -x', '10x^8'],
    ['a * b^3 * a^6 * b^2', 'a^7 * b^5'],
    ['x^2y^3 * x^5y^2', 'x^7y^5'],
    ['2ab^3 * 5a^3b^2', '10a^4b^5'],
    ['3p^6 * -3p^6', '-9p^12'],
    ['6x^2 * -3y * 2y^5 * -2x', '72x^3y^6'],
    ['7a^3 + 5a^3', '12a^3'],
    ['3a^2 - 9a^2', '-6a^2'],
    ['4a^4 - a^4', '3a^4'],
    ['8b^8 - b^8', '7b^8'],
    ['c^5 - 5c^5', '-4c^5'],
    ['12d^2 + 3d^2', '15d^2'],
    ['2y^2 + y^2', '3y^2'],
    ['a^2 + 2a^2', '3a^2'],
    ['x^2 + x^2', '2x^2'],
    ['6a + 8a', '14a'],
    ['6a^2 - 8a^2', '-2a^2'],
    ['2x^2 + 8x^2', '10x^2'],
    ['8ab - 6ab', '2ab'],
    ['13p^2 + p^2', '14p^2'],
    ['5xy + 4xy', '9xy'],
    ['xy - 8xy', '-7xy'],
    ['5a^3 + 5a^3', '10a^3'],
    ['6x^6 - 7x^6', '-x^6'],
    ['2x^2 + 8y^2', '2x^2 + 8y^2'],
    ['8a^2 - 7a^2', 'a^2'],
    ['10a^3 + 3', '10a^3 + 3'],
    ['6a^2 - 10a', '6a^2 - 10a'],
    ['5x^2 - 5x^2', '0'],
    ['7p^3 + 41p^3', '48p^3'],
    ['2a^3 - 5a^3 + 7a^3', '4a^3'],
    ['-x^2 - 2x^2 + 3x^3', '-3x^2 + 3x^3'],
    ['3p^4 - q^3 + p^4 - 2q^3', '4p^4 - 3q^3'],
    ['4m^3 - 3m^5 - 4m^3 + m^4', '-3m^5 + m^4'],
    ['ab + 7a - 6a - 3ab', 'a - 2ab'],
    ['-7 - y^5 + 8 - 2y^5', '-3y^5 + 1'],
    ['3x^5 + 2x^5', '5x^5'],
    ['3x^5 * 2x^5', '6x^10'],
    ['5x^3 - 2x^5', '5x^3 - 2x^5'],
    ['5x^3 * 2x^5', '10x^8'],
    ['x^3 + 2x^3', '3x^3'],
    ['x^3 * 2x^3', '2x^6'],
    ['-3x^4 + 3x^4', '0'],
    ['-3x^4 * 3x^4', '-9x^8'],
    ['-3x^4 - 3x^4', '-6x^4'],
    ['a^3 * a^2 + 3a^5', '4a^5'],
    ['2a^4 * a^2 + 5a^3 * a^3', '7a^6'],
    ['-7a^8 + 2a^2 * -3a^6', '-13a^8'],
    ['8a^2 * 3a^5 - 2a^4 * -3a^3', '30a^7'],
    ['3a^2(a^4 + 2a)', '3a^6 + 6a^3'],
    ['5a(a^3 - 2a)', '5a^4 - 10a^2'],
    ['-3a^2(a - 2)', '-3a^3 + 6a^2'],
    ['a^3(a^5 - a^4)', 'a^8 - a^7'],
    ['a^3(2a - 1) + a^2(a^2 - 3a)', '3a^4 - 4a^3'],
    ['a^2(a^3 - 2a) - a^4(a - 1)', '-2a^3 + a^4'],
]);

it('simplifies powers', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['(ab)^7', 'a^7b^7'],
    ['(pq)^12', 'p^12q^12'],
    ['(-4x)^2', '16x^2'],
    ['(abc)^9', 'a^9b^9c^9'],
    ['(-3a)^3', '-27a^3'],
    ['(-2pq)^4', '16p^4q^4'],
    ['(4xy)^2', '16x^2y^2'],
    ['(10a)^3', '1000a^3'],
    ['(-ab)^3', '-a^3b^3'],
    ['(3a)^2 * (-2a)^3', '-72a^5'],
    ['b^3 * (3b)^3', '27b^6'],
    ['(-4b)^3 * (-b)^5', '64b^8'],
    ['(-2a)^6 * (5ab)^2', '1600a^8b^2'],
    ['(3xy)^4 * xy^2', '81x^5y^6'],
    ['(-2d)^3 * -3d^4', '24d^7'],
    ['(a^2)^6', 'a^12'],
    ['(b^5)^2', 'b^10'],
    ['(a^4)^3', 'a^12'],
    ['(c^8)^2', 'c^16'],
    ['(a^7)^4', 'a^28'],
    ['(p^6)^8', 'p^48'],
    ['a^3 * (a^9)^2', 'a^21'],
    ['x * (x^12)^10', 'x^121'],
    ['(p^3)^4 * (p^6)^3', 'p^30'],
    ['(2a^2)^3', '8a^6'],
    ['(-3a^3)^3', '-27a^9'],
    ['(ab^2)^3', 'a^3b^6'],
    ['(x^3y)^2', 'x^6y^2'],
    ['(-3a^2b)^4', '81a^8b^4'],
    ['(4a^2b^2)^3', '64a^6b^6'],
    ['(ab^3c^3)^4', 'a^4b^12c^12'],
    ['(-xy^6z^5)^7', '-x^7y^42z^35'],
    ['(pq^3)^2 * (p^6q^3)^3', 'p^20q^15'],
    ['(2a)^3 + 4a^3', '12a^3'],
    ['(2a^2)^3 + (3a^3)^2', '17a^6'],
    ['4a^3 * 2a^5 + (3a^4)^2', '17a^8'],
    ['-2x^3 * x + (-3x^2)^2', '7x^4'],
    ['-(-5x^4)^3 + 5(2x^3)^4', '205x^12'],
    ['(5a^3)^2 * 3a^4 - (-2a^5)^2', '71a^10'],
    ['(5a^3)^4 * -3a^2 * 2a^4 + 18(a^9)^2 - 7(a^3)^2 * -(2a^4)^3 + (-9a^9)^2 - 3333a^18', '-6928a^18'],
    ['7(a^3b^4)^6 - 18(a^9)^2 * -(2b^6)^4 + (3ab)^2 * (2a^3b^4)^2 * -10(a^5b^7)^2 + 5(a^3b^3)^2 * 13(a^2b^3)^6', '0'],
]);
