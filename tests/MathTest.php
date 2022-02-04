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
    expect($result)->toBe('5');
});

it('can record steps with simplifications', function () {
    $result = Math::from('4x^2 + 5x * 6x')->config(['withSteps' => true])->simplify()->string();

    expect($result)->toBe([
        'result' => '34x^2',
        'steps' => [
            ['type' => 'simplify', 'name' => 'Multiply like factors', 'result' => '4x^2+5*6x^2'],
            ['type' => 'simplify', 'name' => 'Multiply real numbers', 'result' => '4x^2+30x^2'],
            ['type' => 'simplify', 'name' => 'Add like terms', 'result' => '34x^2'],
        ],
    ]);
});
