<?php

use MathSolver\Math;

it('can simplify expressions', function () {
    $result = (new Math('2x + 9x'))->simplify()->string();
    expect($result)->toBe('11x');
});

it('can be instantiated from a static method', function () {
    $result = Math::from('2x + 3x')->simplify()->string();
    expect($result)->toBe('5x');
});

it('converts automatically to a string', function () {
    $result = (string) Math::from('7x * 3')->simplify();
    expect($result)->toBe('21x');
});

it('has mathjax disabled by default', function () {
    $result = Math::from('root(18, 2)')->simplify()->string();
    expect($result)->toBe('3root(2,2)');
});

it('can use mathjax', function () {
    $result = Math::from('root(20, 2)')->config(['mathjax' => true])->simplify()->string();
    expect($result)->toBe('2\sqrt{5}');
});

it('can substitute values', function () {
    $result = Math::from('2x')->substitute(['x' => 5])->string();
    expect($result)->toBe('2(5)');
});

it('can substitute and simplify values', function () {
    $result = Math::from('3y')->substitute(['y' => 4])->simplify()->string();
    expect($result)->toBe('12');
});

it('can substitute multiple values', function () {
    $result = Math::from('3x + 5y')->substitute(['x' => 4, 'y' => 2])->string();
    expect($result)->toBe('3(4)+5(2)');

    $result = Math::from('3x + 5y')->substitute(['x' => 4, 'y' => 2])->simplify()->string();
    expect($result)->toBe('22');
});

it('can solve equations', function () {
    $result = Math::from('2x = 10')->solveFor('x')->string();
    expect($result)->toBe('x=5');
});

it('can record steps with simplifications', function () {
    $result = Math::from('4x^2 + 5x * 6x')->config(['steps' => true])->simplify()->string();

    expect($result)->toBe([
        'result' => '34x^2',
        'steps' => [
            ['type' => 'simplify', 'name' => 'Multiply like factors', 'result' => '4x^2+5*6x^2'],
            ['type' => 'simplify', 'name' => 'Multiply real numbers', 'result' => '4x^2+30x^2'],
            ['type' => 'simplify', 'name' => 'Add like terms', 'result' => '34x^2'],
        ],
    ]);
});

it('can record steps with mathjax', function () {
    // without mathjax
    $result = Math::from('root(8,2)')->config(['steps' => true, 'mathjax' => false])->simplify()->string();
    expect($result)->toBe([
        'result' => '2root(2,2)',
        'steps' => [['type' => 'simplify', 'name' => 'Simplify roots', 'result' => '2root(2,2)']],
    ]);

    // with mathjax
    $result = Math::from('root(8,2)')->config(['steps' => true, 'mathjax' => true])->simplify()->string();
    expect($result)->toBe([
        'result' => '2\sqrt{2}',
        'steps' => [['type' => 'simplify', 'name' => 'Simplify roots', 'result' => '2\sqrt{2}']],
    ]);
});

it('can record steps for substitution', function () {
    $result = Math::from('2x')->config(['steps' => true])->substitute(['x' => 3])->string();

    expect($result)->toBe([
        'result' => '2(3)',
        'steps' => [
            ['type' => 'substitute', 'name' => 'Substitute x for 3', 'result' => '2(3)'],
        ],
    ]);
});

it('can record steps for substitution with multiple replacements', function () {
    $result = Math::from('2xy')->config(['steps' => true])->substitute(['x' => 3, 'y' => 4])->string();

    expect($result)->toBe([
        'result' => '2(3)(4)',
        'steps' => [
            ['type' => 'substitute', 'name' => 'Substitute x for 3 and y for 4', 'result' => '2(3)(4)'],
        ],
    ]);
});

it('can record steps for substitution with mathjax', function () {
    $result = Math::from('2xy')->config(['steps' => true, 'mathjax' => true])->substitute(['x' => 3, 'y' => 4])->string();

    expect($result)->toBe([
        'result' => '2(3)(4)',
        'steps' => [
            ['type' => 'substitute', 'name' => 'Substitute \( x \) for \( 3 \) and \( y \) for \( 4 \)', 'result' => '2(3)(4)'],
        ],
    ]);
});

it('can record steps for substitution and simplification', function () {
    $result = Math::from('2x')->config(['steps' => true, 'mathjax' => true])->substitute(['x' => 3])->simplify()->string();

    expect($result)->toBe([
        'result' => '6',
        'steps' => [
            ['type' => 'substitute', 'name' => 'Substitute \( x \) for \( 3 \)', 'result' => '2(3)'],
            ['type' => 'simplify', 'name' => 'Remove brackets', 'result' => '2*3'],
            ['type' => 'simplify', 'name' => 'Multiply real numbers', 'result' => '6'],
        ],
    ]);
});

it('can record steps for solving equations', function () {
    $result = Math::from('2x = 10')->config(['steps' => true])->solveFor('x')->string();

    expect($result)->toBe([
        'result' => 'x=5',
        'steps' => [
            ['type' => 'solve', 'name' => 'Multiply both sides by frac(1,2)', 'result' => '2xfrac(1,2)=10frac(1,2)'],
            ['type' => 'simplify', 'name' => 'Multiply fractions', 'result' => 'frac(2,2)*x=frac(10,2)'],
            ['type' => 'simplify', 'name' => 'Simplify fractions', 'result' => '1x=5'],
            ['type' => 'simplify', 'name' => 'Multiply real numbers', 'result' => 'x=5'],
        ],
    ]);
});

it('can record steps for solving equations with mathjax', function () {
    $result = Math::from('x + 5 = 20')->config(['steps' => true, 'mathjax' => true])->solveFor('x')->string();

    expect($result)->toBe([
        'result' => 'x=15',
        'steps' => [
            ['type' => 'solve', 'name' => 'Add \( -5 \) to both sides', 'result' => 'x+5-5=20-5'],
            ['type' => 'simplify', 'name' => 'Add real numbers', 'result' => 'x=15'],
        ],
    ]);
});
