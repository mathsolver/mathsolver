<?php

use MathSolver\Arithmetic\MultiplyRealNumbers;
use MathSolver\Exponents\MultiplyLikeFactorsAndConvertBrokenExponentsIntoRoots;
use MathSolver\Exponents\SimplifyRoots;
use MathSolver\Math;
use MathSolver\Simplify\AddLikeTerms;
use MathSolver\Simplify\RemoveBrackets;

it('can simplify expressions', function () {
    $result = Math::simplify('2x + 9x')->string();
    expect($result)->toBe('11x');
});

it('converts automatically to a string', function () {
    $result = (string) Math::simplify('7x * 3');
    expect($result)->toBe('21x');
});

it('has mathjax disabled by default', function () {
    $result = Math::simplify('sqrt(18)')->string();
    expect($result)->toBe('3sqrt(2)');
});

it('can use mathjax', function () {
    $result = Math::simplify('sqrt(20)')->latex();
    expect($result)->toBe('2\sqrt{5}');
});

it('can record steps with simplifications', function () {
    $result = Math::from('4x^2 + 5x * 6x')->config(['steps' => true])->simplify()->string();

    expect($result)->toBe([
        'result' => '34x^2',
        'steps' => [
            ['name' => 'Multiply real numbers', 'docs' => MultiplyRealNumbers::$docs, 'result' => '4x^2+30xx'],
            ['name' => 'Multiply like factors and convert broken exponents into roots', 'docs' => MultiplyLikeFactorsAndConvertBrokenExponentsIntoRoots::$docs, 'result' => '4x^2+30x^2'],
            ['name' => 'Add like terms', 'docs' => AddLikeTerms::$docs, 'result' => '34x^2'],
        ],
    ]);
});

it('can record steps with mathjax', function () {
    // Without mathjax
    $result = Math::from('sqrt[8]')->config(['steps' => true, 'mathjax' => false])->simplify()->string();
    expect($result)->toBe([
        'result' => '2root[2,2]',
        'steps' => [['name' => 'Simplify roots', 'docs' => SimplifyRoots::$docs, 'result' => '2root[2,2]']],
    ]);

    // With mathjax
    $result = Math::from('sqrt[8]')->config(['steps' => true, 'mathjax' => true])->simplify()->string();
    expect($result)->toBe([
        'result' => '2\sqrt{2}',
        'steps' => [['name' => 'Simplify roots', 'docs' => SimplifyRoots::$docs, 'result' => '2\sqrt{2}']],
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
            ['name' => 'Remove brackets', 'docs' => RemoveBrackets::$docs, 'result' => '2*3'],
            ['name' => 'Multiply real numbers', 'docs' => MultiplyRealNumbers::$docs, 'result' => '6'],
        ],
    ]);
});
