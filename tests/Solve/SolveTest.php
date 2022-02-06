<?php

use MathSolver\Solve\Solver;
use MathSolver\Utilities\StringToTreeConverter;

it('can solve linear equations', function (string $input, string $expected) {
    $tree = StringToTreeConverter::run($input);
    $result = Solver::run($tree, 'x')['result'];
    $expected = StringToTreeConverter::run("x = {$expected}");
    expect($result)->toEqual($expected);
})->with([
    ['x + 3 = 6', '3'],
    ['x + 9 = 10', '1'],
    ['x - 3 = 9', '12'],
    ['x + 4 = -8', '-12'],
    ['5 + x = 8', '3'],
    ['x = 6', '6'],
    ['x + 3 = y + 4', 'y + 1'],
    ['x + 7 = 10', '3'],
    ['x + 1 = 0', '-1'],
    ['9 + x = 11', '2'],
    ['5x = 25', '5'],
    ['3x = 27', '9'],
    ['8x = 40', '5'],
    ['28x = 1316', '47'],
    ['-2x = 4', '-2'],
    ['2x + 5 = 15', '5'],
    ['-5x + 8 = 53', '-9'],
    ['6 - x = 4', '2'],
    ['10x - 12 = -12', '0'],
    ['-16x = -4', 'frac(1, 4)'],
    ['14x + 3 = 2', 'frac(-1, 14)'],
    ['-6 - x = -4', '-2'],
    ['frac(1, 2) - 4x = frac(9, 2)', '-1'],
    ['4x + 17 = 29', '3'],
    ['-3x + 1 = 16', '-5'],
    ['7x + 1 = 8', '1'],
    ['6x + 24 = 0', '-4'],
    ['9x + 8 = 8', '0'],
    ['3x + 5 = 23', '6'],
    ['2x + 8 = -20', '-14'],
    ['-5x + 7 = 17', '-2'],
    ['2x + frac(1, 2) = frac(13, 2)', '3'],
    ['4x = 20', '5'],
    ['x + 4 = 20', '16'],
    ['4x = 3', 'frac(3, 4)'],
    ['x + 4 = 3', '-1'],
    ['2x = -20', '-10'],
    ['x + 2 = -20', '-22'],
    ['x + 8 = -9', '-17'],
    ['8x = -80', '-10'],
    ['8 + 5x = 28', '4'],
    ['2 + 3x = 2', '0'],
    ['25 + x = 15', '-10'],
    ['25x = 15', 'frac(3, 5)'],
    ['4x - 17 = -9', '2'],
    ['-8x - 1 = -9', '1'],
    ['-5 + 2x = 17', '11'],
    ['-9 - x = 11', '-20'],
    ['4x - 8 = -20', '-3'],
    ['-3x = 12', '-4'],
    ['15x + 35 = 65', '2'],
    ['8x - 18 = 30', '6'],
    ['22 + 16x = 102', '5'],
    ['3x - 6 = -9', '-1'],
    ['-2x - 7 = -23', '8'],
    ['6 - 3x = 27', '-7'],
    ['5x + 8 = 53', '9'],
    ['-5x - 6 = -6', '0'],
    ['-8x = -2', 'frac(1, 4)'],
    ['-8 - 6x = 4', '-2'],
    ['-8 - x = -2', '-6'],
    ['7x + 18 = 60', '6'],
]);

it('can solve for other letters', function () {
    $tree = StringToTreeConverter::run('2a + 4 = 10');
    $result = Solver::run($tree, 'a')['result'];
    expect($result)->toEqual(StringToTreeConverter::run('a = 3'));

    $tree = StringToTreeConverter::run('7b + 3 = 31');
    $result = Solver::run($tree, 'b')['result'];
    expect($result)->toEqual(StringToTreeConverter::run('b = 4'));
});

it('records steps when subtracting', function () {
    $tree = StringToTreeConverter::run('x + 4 = 10');
    $result = Solver::run($tree, 'x');

    expect($result)->toEqual([
        'result' => StringToTreeConverter::run('x = 6'),
        'steps' => [
            ['type' => 'solve', 'name' => 'Add -4 to both sides', 'result' => 'x+4-4=10-4'],
            ['type' => 'simplify', 'name' => 'Add real numbers', 'result' => 'x=6'],
        ],
    ]);

    $tree = StringToTreeConverter::run('x + 4 + y = 10');
    $result = Solver::run($tree, 'x');

    expect($result)->toEqual([
        'result' => StringToTreeConverter::run('x = -y + 6'),
        'steps' => [
            ['type' => 'solve', 'name' => 'Add -4 and -1y to both sides', 'result' => 'x+4+y-4-1y=10-4-1y'],
            ['type' => 'simplify', 'name' => 'Add like terms', 'result' => 'x+4-4=-1y+10-4'],
            ['type' => 'simplify', 'name' => 'Add real numbers', 'result' => 'x=-1y+6'],
        ],
    ]);
});

it('records steps when dividing', function () {
    $tree = StringToTreeConverter::run('2x = 16');
    $result = Solver::run($tree, 'x');

    expect($result)->toEqual([
        'result' => StringToTreeConverter::run('x = 8'),
        'steps' => [
            ['type' => 'solve', 'name' => 'Multiply both sides by frac(1,2)', 'result' => '2xfrac(1,2)=16frac(1,2)'],
            ['type' => 'simplify', 'name' => 'Multiply fractions', 'result' => 'frac(2,2)*x=frac(16,2)'],
            ['type' => 'simplify', 'name' => 'Simplify fractions', 'result' => '1x=8'],
            ['type' => 'simplify', 'name' => 'Multiply real numbers', 'result' => 'x=8'],
        ],
    ]);

    $tree = StringToTreeConverter::run('2xy = 16');
    $result = Solver::run($tree, 'x');

    expect($result)->toEqual([
        'result' => StringToTreeConverter::run('x = 8y^-1'),
        'steps' => [
            ['type' => 'solve', 'name' => 'Multiply both sides by frac(1,2) and y^-1', 'result' => '2xyfrac(1,2)*y^-1=16frac(1,2)*y^-1'],
            ['type' => 'simplify', 'name' => 'Multiply fractions', 'result' => 'frac(2,2)*xyy^-1=frac(16,2)*y^-1'],
            ['type' => 'simplify', 'name' => 'Multiply like factors', 'result' => 'frac(2,2)*xy^0=frac(16,2)*y^-1'],
            ['type' => 'simplify', 'name' => 'Simplify fractions', 'result' => '1xy^0=8y^-1'],
            ['type' => 'simplify', 'name' => 'Exponent of zero', 'result' => '1*1x=8y^-1'],
            ['type' => 'simplify', 'name' => 'Multiply like factors', 'result' => 'x*1^2=8y^-1'],
            ['type' => 'simplify', 'name' => 'Calculate powers of real numbers', 'result' => '1x=8y^-1'],
            ['type' => 'simplify', 'name' => 'Multiply real numbers', 'result' => 'x=8y^-1'],
        ],
    ]);
});

it('records steps when subtracting and dividing', function () {
    // without mathjax
    $tree = StringToTreeConverter::run('5x + 7 = 22');
    $result = Solver::run($tree, 'x');

    expect($result)->toEqual([
        'result' => StringToTreeConverter::run('x = 3'),
        'steps' => [
            ['type' => 'solve', 'name' => 'Add -7 to both sides', 'result' => '5x+7-7=22-7'],
            ['type' => 'simplify', 'name' => 'Add real numbers', 'result' => '5x=15'],
            ['type' => 'solve', 'name' => 'Multiply both sides by frac(1,5)', 'result' => '5xfrac(1,5)=15frac(1,5)'],
            ['type' => 'simplify', 'name' => 'Multiply fractions', 'result' => 'frac(5,5)*x=frac(15,5)'],
            ['type' => 'simplify', 'name' => 'Simplify fractions', 'result' => '1x=3'],
            ['type' => 'simplify', 'name' => 'Remove redundant numbers', 'result' => 'x=3'],
        ],
    ]);

    // with mathjax
    $tree = StringToTreeConverter::run('5x + 7 = 22');
    $result = Solver::run($tree, 'x', $mathjax = true);

    expect($result)->toEqual([
        'result' => StringToTreeConverter::run('x = 3'),
        'steps' => [
            ['type' => 'solve', 'name' => 'Add \( -7 \) to both sides', 'result' => '5x+7-7=22-7'],
            ['type' => 'simplify', 'name' => 'Add real numbers', 'result' => '5x=15'],
            ['type' => 'solve', 'name' => 'Multiply both sides by \( \frac{1}{5} \)', 'result' => '5x*\frac{1}{5}=15*\frac{1}{5}'],
            ['type' => 'simplify', 'name' => 'Multiply fractions', 'result' => '\frac{5}{5}*x=\frac{15}{5}'],
            ['type' => 'simplify', 'name' => 'Simplify fractions', 'result' => '1x=3'],
            ['type' => 'simplify', 'name' => 'Remove redundant numbers', 'result' => 'x=3'],
        ],
    ]);
});
