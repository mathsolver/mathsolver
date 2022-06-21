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
    $result = Math::from('sqrt[18]')->simplify()->string();
    expect($result)->toBe('3root[2,2]');
});

it('can use mathjax', function () {
    $result = Math::from('sqrt[20]')->config(['mathjax' => true])->simplify()->string();
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

it('can record steps with simplifications', function () {
    $result = Math::from('4x^2 + 5x * 6x')->config(['steps' => true])->simplify()->string();

    expect($result)->toBe([
        'result' => '34x^2',
        'steps' => [
            ['type' => 'simplify', 'name' => 'Multiply real numbers', 'result' => '4x^2+30xx'],
            ['type' => 'simplify', 'name' => 'Multiply like factors and convert broken exponents into roots', 'result' => '4x^2+30x^2'],
            ['type' => 'simplify', 'name' => 'Add like terms', 'result' => '34x^2'],
        ],
    ]);
});

it('can record steps with mathjax', function () {
    // Without mathjax
    $result = Math::from('sqrt[8]')->config(['steps' => true, 'mathjax' => false])->simplify()->string();
    expect($result)->toBe([
        'result' => '2root[2,2]',
        'steps' => [['type' => 'simplify', 'name' => 'Simplify roots', 'result' => '2root[2,2]']],
    ]);

    // With mathjax
    $result = Math::from('sqrt[8]')->config(['steps' => true, 'mathjax' => true])->simplify()->string();
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
