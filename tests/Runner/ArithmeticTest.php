<?php

use MathSolver\Runner;
use MathSolver\Utilities\StringToTreeConverter;

it('can add and subtract', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['53 + 8', '61'],
    ['173 + 6', '179'],
    ['800 + 30', '830'],
    ['12 - 9', '3'],
    ['83 - 7', '76'],
    ['500 - 17', '483'],
    ['64 + 89 + 36', '189'],
    ['17 + 58 + 183', '258'],
    ['600 + 800', '1400'],
    ['539 - 98', '441'],
    ['5616 - 199', '5417'],
    ['68 + 49', '117'],
    ['8210 - 1993', '6217'],
    ['127 + 77 + 423', '627'],
    ['83 - 17', '66'],
    ['329 - 85 + 471', '715'],
    ['893 - 87 + 107', '913'],
    ['199 + 93', '292'],
    ['5 + 7', '12'],
    ['500 + 700', '1200'],
    ['5000 + 70', '5070'],
    ['23 - 7', '16'],
    ['2300 - 700', '1600'],
    ['2300 - 70', '2230'],
    ['2300 + 700', '3000'],
    ['7500 - 3200', '4300'],
    ['8000 - 111', '7889'],
    ['4327 + 4673', '9000'],
    ['732 + 196', '928'],
    ['996 - 399', '597'],
    ['632 - 185 + 268', '715'],
    ['5036 - 97 + 18', '4957'],
    ['693 + 307 + 8000', '9000'],
    ['9063 - 387 - 90', '8586'],
    ['1700 - 800 - 109', '791'],
    ['542 + 193 + 507', '1242'],
    ['-4 + 6', '2'],
    ['-9 + 5', '-4'],
    ['-6 - 4', '-10'],
    ['-5 - 9', '-14'],
    ['0 + 17', '17'],
    ['-3 + 3', '0'],
    ['-15 - 25', '-40'],
    ['-8 - 26', '-34'],
    ['6 - 8', '-2'],
    ['-1 + 3', '2'],
    ['-4 - 9', '-13'],
    ['-2 + 10', '8'],
    ['27 - 31', '-4'],
    ['-8 - 21', '-29'],
    ['12 - 10', '2'],
    ['-6 + 33', '27'],
    ['-76 - 29', '-105'],
    ['57 - 62', '-5'],
    ['-13 + 23', '10'],
    ['-59 + 83', '24'],
    ['-213 - 76', '-289'],
    ['-131 + 67', '-64'],
    ['456 - 456', '0'],
    ['-765 - 0', '-765'],
    ['-5 - (13 - 7)', '-11'],
    ['-12 - 8 - 1', '-21'],
    ['-12 - (8 - 1)', '-19'],
    ['35 - (12 + 43)', '-20'],
    ['5 - 8 - (12 - 7)', '-8'],
    ['13 - 48 - (11 + 27)', '-73'],
    ['-20 + -42', '-62'],
    ['-14 - 23', '-37'],
    ['8 + -3 + -11', '-6'],
    ['13 - 24 + -35', '-46'],
    ['23 - (63 + -27)', '-13'],
    ['-2 + (42 + -69)', '-29'],
    ['5 + -1', '4'],
    ['10 + -5', '5'],
    ['-9 + -1', '-10'],
    ['-5 + -6', '-11'],
    ['-6 + -20', '-26'],
    ['0 + -8', '-8'],
    ['13 + -21', '-8'],
    ['19 + -19', '0'],
    ['-21 + -21', '-42'],
    ['-8 + -3', '-11'],
    ['-8 + 3', '-5'],
    ['8 + -3', '5'],
    ['-8 - 3', '-11'],
    ['8 - 3', '5'],
    ['-3 - 8', '-11'],
    ['3 + -8', '-5'],
    ['-3 + -8', '-11'],
    ['-3 + 8', '5'],
    ['-11 - (14 + -9)', '-16'],
    ['-75 + -18 + -23', '-116'],
    ['101 - (39 + -12)', '74'],
]);

it('multiply and divide', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['7 * 4', '28'],
    ['3 * 8', '24'],
    ['5 * 3', '15'],
    ['8 * 4', '32'],
    ['80 * 40', '3200'],
    ['800 * 40', '32000'],
    ['9 * 4', '36'],
    ['700 * 30', '21000'],
    ['400 * 9000', '3600000'],
    ['56 / 8', '7'],
    ['28 / 7', '4'],
    ['24 / 3', '8'],
    ['640 / 8', '80'],
    ['1800 / 600', '3'],
    ['3600 / 60', '60'],
    ['1800 / 90', '20'],
    ['54000 / 60', '900'],
    ['6900 / 3', '2300'],
    ['14 * 45', '630'],
    ['18 * 55', '990'],
    ['24 * 25', '600'],
    ['4 * 38', '152'],
    ['96 / 16', '6'],
    ['288 / 36', '8'],
    ['192 / 24', '8'],
    ['10800 / 12', '900'],
    ['805 / 23', '35'],
    ['7 * -30', '-210'],
    ['8 * -5', '-40'],
    ['-6 * 30', '-180'],
    ['-1 * 12 * 3', '-36'],
    ['18 * -1 * 2', '-36'],
    ['0 * -300 * 57', '0'],
    ['25 * -8 * 4 * 1', '-800'],
    ['-1 * 1 * 4 * 0 * 3', '0'],
    ['-7 * 4 * 2 * 5', '-280'],
    ['-8 * -3', '24'],
    ['-9 * 0', '0'],
    ['12 * -70', '-840'],
    ['-12 * 5 * -1', '60'],
    ['-99 * 10  * -10', '9900'],
    ['18 * -5 * 10', '-900'],
    ['-5 * -4 * 3 * -2', '-120'],
    ['-2 * 6 * 3 * -5', '180'],
    ['-3 * 3 * -5 * -5', '-225'],
    ['2 * 7 * -5', '-70'],
    ['2 * -7 * -5', '70'],
    ['-2 * -7 * -5', '-70'],
    ['-5 * -4 * 3', '60'],
    ['-5 * 4 * 3', '-60'],
    ['-5 * -4 * -3', '-60'],
    ['-16 * -17 * 0 * 8', '0'],
    ['-1 * -1 * -1 * -1 * 1', '1'],
    ['-18 * 0 * 312 * 17', '0'],
    ['-48 / 12', '-4'],
    ['27 / -3', '-9'],
    ['52 / -52', '-1'],
    ['-42 / -3', '14'],
    ['-50 / 1', '-50'],
    ['17 / -1', '-17'],
    ['10 / -10', '-1'],
    ['0 / -10', '0'],
    ['-3 / -1', '3'],
    ['-17 / -17', '1'],
    [' (7 + 8) / -5 - 8', '-11'],
    ['18 / -6 - 5 / -1', '2'],
    ['-24 / -6 - 12 / (-4 + 2)', '10'],
    ['(-8 - 2) / -10 - 20 * -0.5', '11'],
    ['18 * -3 / 6 - 8', '-17'],
    ['-2 * 3 / -6 - 1 * -2', '3'],
    ['(25 - 50) / 5 * -10 - 8', '42'],
    ['((8 - 10) / -2 + 5) / -3', '-2'],
    ['(-8 * 5) / (-6 + -4)', '4'],
    ['(-12 * -5) - (-20 / 2)', '70'],
    ['8 / (2 * -4) - 12 / 2', '-7'],
    ['8 - ((10 - 10) / 4 + 5 * -2)', '18'],
    ['9 + 6 * 5', '39'],
    ['(9 + 3) * 7 - 80', '4'],
    ['20 - 2 * 8 - 4', '0'],
    ['8 + 3 * (7 + 2)', '35'],
    ['(8 + 3) * (8 / 2)', '44'],
    ['20 + 64 / (8 / 4)', '52'],
    ['20 - 64 / 8 / 4', '18'],
    ['6 - 3 * (16 / (2 + 6))', '0'],
    ['128 / 4 - (25 - 17) * 4 + 48 / 12 - 4', '0'],
    ['(9 * 6 - 18 - 8 * 3) / 6 + 5 * 3', '17'],
    ['800 - (300 - (200 - 150) * 2) - 450', '150'],
    ['1800 / (600 - (2 * 250 - 200))', '6'],
    ['45 - 3 * (8 - 4 * (5 - 2 * (3 - 1)))', '33'],
    ['8000 + 20 * 30 * (50 - 6 * (45 - 40))', '20000'],
    ['120 / 2 * (3 + (2 * 12 - 23))', '240'],
    ['(((28 / 7 + 2) * 2 + 5) - 3) * 5', '70'],
    ['9 - 5 * -3', '24'],
    ['-3 - 0 * -5 + 6', '3'],
    ['-2 - (5 - 5) * -6', '-2'],
    ['8 - (3 - 5) * -4', '0'],
    ['(8 - 3) * -5 - 4', '-29'],
    ['(-1 - 6) * -1 - 4 * -2', '15'],
    ['11 * -3 - 8 * -4', '-1'],
    ['-2 - 3 * (4 - 5) - 6', '-5'],
    ['-7 - (2 - 5) * -4 + 9', '-10'],
    ['-3 - (3 - 3) * 3 - 3', '-6'],
    ['-3 * -3 * -3 - 3', '-30'],
    ['-3 * -3 - 3 * -3 - 3 * -3', '27'],
]);

it('can calculate with fractions', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['15/25', '3/5'],
    ['15/27', '5/9'],
    ['18/36', '1/2'],
    ['28/35', '4/5'],
    ['20/100', '1/5'],
    ['56/40', '7/5'],
    ['35/90', '7/18'],
    ['60/12', '5'],
    ['32/32', '1'],
    ['-18/9', '-2'],
    ['-36/6', '-6'],
    ['-21/-1', '21'],
    ['-81/-9', '9'],
    ['0/-3', '0'],
    ['-21/21', '-1'],
    ['27/-9', '-3'],
    ['-12/18', '-2/3'],
    ['30/-12', '-5/2'],
    ['-21/-14', '3/2'],
    ['-125/15', '-25/3'],
    ['27/-9', '-3'],
    ['11/-99', '-1/9'],
    ['1/2 + 1/3', '5/6'],
    ['1/2 + 1/4', '3/4'],
    ['3/4 - 1/3', '5/12'],
    ['3/2 - 1/4', '5/4'],
    ['7/8 - 8/64', '3/4'],
    ['3/5 + 2/3 + 1/6', '43/30'],
    ['11/5 - 3/4 + 7/10', '43/20'],
    ['43/8 - (5/4 + 5/2)', '13/8'],
    ['28 - (3 + 14/3)', '61/3'],
    ['5/8 * 3/7', '15/56'],
    ['2/9 * 7/5', '14/45'],
    ['3 * 2/9', '2/3'],
    ['5/4 * 7/5', '7/4'],
    ['1/5 * 15', '3'],
    ['3/4 * 80', '60'],
    ['3/8 * 2/7', '3/28'],
    ['3/8 + 2/7', '37/56'],
    ['7/4 * 2/5', '7/10'],
    ['7/4 + 2/5', '43/20'],
    ['9/4 * 8/3', '6'],
    ['9/4 + 8/3', '59/12'],
    ['-5/12 + 7/12', '1/6'],
    ['-2/9 - 4/9', '-2/3'],
    ['8/5 - 4', '-12/5'],
    ['3/4 - 13/12', '-1/3'],
    ['-23/6 - 3/4', '-55/12'],
    ['-1/11 + 12/10', '61/55'],
    ['-8/5 + 19/5', '11/5'],
    ['5/3 - 13/4', '-19/12'],
    ['2/3 - 5', '-13/3'],
    ['-4 - 7/2', '-15/2'],
    ['-7/6 + 8/3', '3/2'],
    ['-7/3 - 1/9', '-22/9'],
    ['-2/3 * 5/11', '-10/33'],
    ['-2/5 * -3/4', '3/10'],
    ['5/3 * -1/5', '-1/3'],
    ['-6/5 * 10/3', '-4'],
    ['-3/7 * -7/3', '1'],
    ['-5 * -2/11', '10/11'],
    ['-5/7 - 2/3', '-29/21'],
    ['-5/7 * -2/3', '10/21'],
    ['3/4 - 2/7 - 1/2', '-1/28'],
    ['3/4 * -2/7 * -1/2', '3/28'],
    ['-7/3 - 17/5', '-86/15'],
    ['-7/3 * -17/5', '119/15'],
    ['-2/3 + 7/5 * -20/7', '-14/3'],
    ['1/3 * 3/4 + 1/2 * 7/6', '5/6'],
    ['5 * -3/7 - 4/3 * 3/4', '-22/7'],
    ['1/3 * (1/2 - 5/6) - 5/2', '-47/18'],
    ['19/6 - 3/2 * (1/3 - 5/4)', '109/24'],
    ['(17/14 + 1/2) * (5/3 - 9/4)', '-1'],
    ['(2/5) / (3/4)', '8/15'],
    ['(-1/5) / (3/7)', '-7/15'],
    ['(-5/4) / (-5/2)', '1/2'],
    ['18 / (-2/9)', '-81'],
    ['(-5/8) / -3', '5/24'],
    ['(-15/7) / -5', '3/7'],
    ['(-1/2) / (-4/3)', '3/8'],
    ['(-1/2) * (-4/3)', '2/3'],
    ['-1/2 - 4/3', '-11/6'],
    ['(9/4) / (-4/3)', '-27/16'],
    ['(9/4) * (-4/3)', '-3'],
    ['9/4 - 4/3', '11/12'],
    ['15 / (-5/4) - 7/3', '-43/3'],
    ['15 * (-5/4) - 7/3', '-253/12'],
    ['15 - 5/4 - 7/3', '137/12'],
    ['5/3 + 3/4 * 3/5', '127/60'],
    ['8/3 + 3/2 * 5/9', '7/2'],
    ['(9/4 - 1/3) * 12/23', '1'],
    ['5 - 3/8 * 4/3', '9/2'],
    ['4 * 1/3 * 3/4', '1'],
    ['11/6 * 6000', '11000'],
    ['120 * (1 - (1/3 + 1/4 + 1/6))', '30'],
    ['(-4 * (5 - -10)) / (-4 + 1)', '20'],
    ['5 * (6 + 18 / -3) / (-5 * -1) - 12 / (3 * -4)', '1'],
    ['(-18 - 6) / (-4 * 3)', '2'],
    ['(-18 - 12) / 2 * -5', '75'],
    ['15 / -3 - 8 * -2 + 3', '14'],
    ['(-21 - -12) / (10 - 1) + 9/-9', '-2'],
    ['15/-15 + -21/-3 - 7 * -1', '13'],
    ['5 * (8 - 29) / -7 - (24 / (18 / -3) - 8) * 3', '51'],
]);

it('can calculate with decimal numbers', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['500 + 3', '503'],
    ['50 + 3', '53'],
    ['5 + 3', '8'],
    ['0.5 + 3', '3.5'],
    ['500 + 0.3', '500.3'],
    ['50 + 0.3', '50.3'],
    ['5 + 0.3', '5.3'],
    ['0.5 + 0.3', '0.8'],
    ['500 + 0.03', '500.03'],
    ['50 + 0.03', '50.03'],
    ['5 + 0.03', '5.03'],
    ['0.5 + 0.03', '0.53'],
    ['500.5 + 0.3', '500.8'],
    ['50.5 - 0.3', '50.2'],
    ['5.5 + 0.3', '5.8'],
    ['0.55 - 0.3', '0.25'],
    ['500.5 + 0.03', '500.53'],
    ['50.5 - 0.03', '50.47'],
    ['5.5 + 0.03', '5.53'],
    ['0.5 - 0.03', '0.47'],
    ['500.05 + 0.3', '500.35'],
    ['50.05 - 0.3', '49.75'],
    ['5.05 + 0.3', '5.35'],
    ['0.05 - 0.03', '0.02'],
    ['500.5 - 0.3', '500.2'],
    ['50.5 + 0.3', '50.8'],
    ['5.5 - 0.3', '5.2'],
    ['0.55 + 0.3', '0.85'],
    ['500.5 - 0.03', '500.47'],
    ['50.5 + 0.03', '50.53'],
    ['5.5 - 0.03', '5.47'],
    ['0.55 + 0.03', '0.58'],
    ['500.05 - 0.3', '499.75'],
    ['50.05 + 0.3', '50.35'],
    ['5.05 - 0.3', '4.75'],
    ['0.55 + 0.33', '0.88'],
    ['2.3 + 0.6', '2.9'],
    ['2.3 + 0.7', '3'],
    ['2.3 + 0.8', '3.1'],
    ['2.3 + 6.2', '8.5'],
    ['2.3 + 6.8', '9.1'],
    ['2.3 + 7', '9.3'],
    ['2.3 + 9.4', '11.7'],
    ['2.3 + 7.8', '10.1'],
    ['2.3 + 9.9', '12.2'],
    ['1.4 + 2.2 + 6.1', '9.7'],
    ['3.8 + 2.5 + 0.9', '7.2'],
    ['3.1 + 6.9 + 2.7', '12.7'],
    ['4.2 + 1.7 + 3.6', '9.5'],
    ['6.3 + 2.1 + 7.5', '15.9'],
    ['8.8 + 4.3 + 10.4', '23.5'],
    ['8.9 - 2.4', '6.5'],
    ['8.4 - 2.4', '6'],
    ['8.4 - 2.9', '5.5'],
    ['13.7 - 6.6', '7.1'],
    ['13.6 - 6.7', '6.9'],
    ['13.6 - 9.9', '3.7'],
    ['25.3 - 16.2', '9.1'],
    ['25.2 - 16.3', '8.9'],
    ['25.0 - 16.1', '8.9'],
    ['3.62 + 9.4 + 2.87', '15.89'],
    ['1.06 + 2.98 + 4.43', '8.47'],
    ['9.43 + 7.22 + 13.05', '29.7'],
    ['14.021 + 8.3 + 11.574', '33.895'],
    ['8.1 - 6.85', '1.25'],
    ['16.76 - 9.89', '6.87'],
    ['15.052 - 3.274', '11.778'],
    ['16.2 - 12.306', '3.894'],
    ['3 * 2.2', '6.6'],
    ['5 * 1.4', '7'],
    ['6 * 6.2', '37.2'],
    ['7.1 * 9', '63.9'],
    ['5.2 * 7', '36.4'],
    ['3.9 * 3', '11.7'],
    ['6 * 8.7', '52.2'],
    ['9.8 * 4', '39.2'],
    ['5 * 8.8', '44'],
    ['3.14 * 5.2', '16.328'],
    ['14.28 * 6.5', '92.82'],
    ['9.7 * 12.3', '119.31'],
    ['7.13 * 2.05', '14.6165'],
    ['3.14 * 12.78', '40.1292'],
    ['1.98 * 0.87', '1.7226'],
]);

it('can do calculations and round numbers', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['calc[8.86, 1]', '8.9'],
    ['calc[12.314, 2]', '12.31'],
    ['calc[7.653685, 3]', '7.654'],
    ['calc[123.498, 0]', '123'],
    ['calc[35.46528, 3]', '35.465'],
    ['calc[35.46528, 2]', '35.47'],
    ['calc[35.46528, 1]', '35.5'],
    ['calc[35.46528, 0]', '35'],
    ['calc[8.9595, 3]', '8.96'],
    ['calc[8.9595, 2]', '8.96'],
    ['calc[8.9595, 1]', '9'],
    ['calc[8.9595, 0]', '9'],
    ['calc[8257139.9, -6]', '8000000'],
    ['calc[8257139.9, -3]', '8257000'],
    ['calc[8257139.9, -1]', '8257140'],
    ['calc[8257139.9, -1]', '8257140'],
    ['calc[6.32 * 0.51, 2]', '3.22'],
    ['calc[8.05 + 123 / (71 + 8.3), 2]', '9.6'],
    ['calc[6.32 / 0.51 * 0.04, 2]', '0.5'],
    ['calc[(10101 / 1101) / (1011 / 101), 2]', '0.92'],
    ['calc[5.6^3, 2]', '175.62'],
    ['calc[0.95^4 * 5.2, 2]', '4.24'],
    ['calc[(-2.6)^4 - 1.7^3, 2]', '40.78'],
    ['calc[0.58 * 2.5^3 - (-1.4)^3, 2]', '11.81'],
    ['calc[(3.7 * 0.27)^5, 2]', '1'],
    ['calc[(4.7 - 2.8)^3 / (-1.8)^4, 2]', '0.65'],
    ['calc[(10/7)^4, 2]', '4.16'],
    ['calc[(2.1^3 - 8) / (1.5^4 - 3), 2]', '0.61'],
    ['calc[(7/3)^3 + (-7/6)^4, 2]', '14.56'],
    ['calc[(-2.3)^5 / (3^2 - 1.3^4), 2]', '-10.48'],
    ['calc[(-5^4 / 7) / (3/4)^3, 2]', '-211.64'],
    ['calc[100 / (5 - 2.8^4), 2]', '-1.77'],
]);

it('can calculate squares', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['2^2', '4'],
    ['4^2', '16'],
    ['16^2', '256'],
    ['5 * 4^2', '80'],
    ['5^2 * 4', '100'],
    ['5^2 - 4^2', '9'],
    ['8^2 - 16 * (1/2)^2', '60'],
    ['(8 - 3)^2 * 5', '125'],
    ['6^2 / 9', '4'],
    ['(3/4 * 8)^2', '36'],
    ['1^2 + (3/4)^2 + (3/2)^2', '61/16'],
    ['(9/8) / (1/4)^2', '18'],
    ['3 + 4^2 * 3', '51'],
    ['(4 + 2)^2 * (1/3)^2', '4'],
    ['3 - (5 - 2)^2 / 3', '0'],
    ['(17 + 3)^2 / 80 - 40', '-35'],
    ['-1/6 - (5/9 + (2/3)^2) * (1/2)^2', '-5/12'],
    ['(1/5 + 3/10)^2 - (1/2)^2', '0'],
    ['(-9)^2', '81'],
    ['-11^2', '-121'],
    ['13^2', '169'],
    ['-(-13)^2', '-169'],
    ['(-15)^2', '225'],
    ['(-1/6)^2', '1/36'],
    ['6^2 + (-3)^2', '45'],
    ['3 * -5^2', '-75'],
    ['-3 * (-5)^2', '-75'],
    ['8^2 - 1^2', '63'],
    ['-7^2 + 3 * 4^2', '-1'],
    ['-(-1/4)^2 + 1/2 * 1/4', '1/16'],
    ['-5 * -8^2', '320'],
    ['5 - 8^2', '-59'],
    ['5 * (-8)^2', '320'],
    ['(5 * -8)^2', '1600'],
    ['-(5 - 8)^2', '-9'],
    ['-5 - (-8)^2', '-69'],
    ['-5^2 + 8 * 3^2', '47'],
    ['(-8)^2 / 4^2', '4'],
    ['18 / (-(1/3)^2)', '-162'],
    ['5/18 - (-1/3)^2', '1/6'],
    ['-(-8)^2 + 4 * -3^2', '-100'],
    ['-1 - 5 * (-2/25)^2', '-129/125'],
]);

it('can calculate with square roots', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['sqrt[9]', '3'],
    ['sqrt[121]', '11'],
    ['sqrt[1]', '1'],
    ['sqrt[0]', '0'],
    ['sqrt[256]', '16'],
    ['sqrt[400]', '20'],
    ['sqrt[625]', '25'],
    ['sqrt[1/9]', '1/3'],
    ['sqrt[4/25]', '2/5'],
    ['sqrt[9] - sqrt[25]', '-2'],
    ['9 + sqrt[25]', '14'],
    ['9 * sqrt[25]', '45'],
    ['sqrt[9] * sqrt[25]', '15'],
    ['2 * sqrt[9] + 3 * sqrt[25]', '21'],
    ['(sqrt[25])^2', '25'],
    ['sqrt[16] - sqrt[49]', '-3'],
    ['sqrt[25] + 1/2 * sqrt[36]', '8'],
    ['sqrt[144] - 3 * sqrt[100]', '-18'],
    ['sqrt[4] + sqrt[1] + sqrt[0]', '3'],
    ['sqrt[4] * sqrt[1] * sqrt[0]', '0'],
    ['3 * sqrt[625] - 2 * sqrt[900]', '15'],
    ['sqrt[1/4] + 10 * sqrt[1/25]', '5/2'],
    ['6 * sqrt[4/9] - 5 * sqrt[81/100]', '-1/2'],
    ['sqrt[25/4] * sqrt[36/25]', '3'],
]);

it('can calculate powers', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Runner::run($tree)['result'];
    $expected = StringToTreeConverter::run($expected);
    expect($result)->toEqual($expected);
})->with([
    ['2^5', '32'],
    ['6^3', '216'],
    ['10^5', '100000'],
    ['2^6', '64'],
    ['1^7', '1'],
    ['0^8', '0'],
    ['1^9999', '1'],
    ['0^2000', '0'],
    ['2^3 * 5', '40'],
    ['2 * 5^3', '250'],
    ['2^3 - 5^3', '-117'],
    ['(5 - 2)^4 + 2', '83'],
    ['(3 * 4)^2 - 8', '136'],
    ['3 * 7^2 - 8', '139'],
    ['2^5 - 5^2', '7'],
    ['(2^3 + 3)^2', '121'],
    ['12 - 6^2', '-24'],
    ['6^2 / 3^2', '4'],
    ['5 * (3 - 2)^3', '5'],
    ['5 - 3 * 2^3', '-19'],
    ['(-2)^4', '16'],
    ['-2^4', '-16'],
    ['(-3)^3', '-27'],
    ['-3^3', '-27'],
    ['5 + (-2)^4', '21'],
    ['5 - 2^4', '-11'],
    ['(-1)^6 - 3^3', '-26'],
    ['1^2 - (-2)^3', '9'],
    ['0^10 - (-1)^10', '-1'],
    ['-5 - (-2)^4', '-21'],
    ['-5^2 - (-2)^5', '7'],
    ['(5 * -2)^4', '10000'],
    ['5 - (2 - 3)^6', '4'],
    ['-3^4 + (-3)^4', '0'],
    ['-1^4 + (-1)^5', '-2'],
    ['32 / (-2)^5', '-1'],
    ['6^3 - 2 * (-3)^2', '198'],
    ['-5^2 - 3^2 * (-2)^3', '47'],
    ['3^-3', '1/27'],
    ['6^-2', '1/36'],
    ['4^0', '1'],
    ['1^-7', '1'],
    ['(1/2)^-1', '2'],
    ['(2/3)^-2', '9/4'],
    ['3^6 * 3^-5', '3'],
    ['6^3 * 6^-4', '1/6'],
    ['5 * (2^3)^-2', '5/64'],
    ['(3^-1)^-3 * 3^3', '729'],
    ['6^5 * 6^-7 * 6^0', '1/36'],
    ['5^-1 * 4 * 5^-2', '4/125'],
    ['2 * 2^-3 * 1/4', '1/16'],
    ['((2^3)^0)^-1', '1'],
    ['1/27 * 3^5 * 1/3', '3'],
    ['3 / 2^-2', '12'],
]);
