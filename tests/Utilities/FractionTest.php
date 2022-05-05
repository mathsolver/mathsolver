<?php

use MathSolver\Utilities\Fraction;
use MathSolver\Utilities\Node;

it('can instantiate a fraction', function () {
    $fraction = new Fraction(2, 3);
    expect($fraction->numerator())->toBe(2);
    expect($fraction->denominator())->toBe(3);
});

test('the denominator is by default 1', function () {
    $fraction = new Fraction(5);
    expect($fraction->numerator())->toBe(5);
    expect($fraction->denominator())->toBe(1);
});

it('can convert to a node', function () {
    $node = new Node('frac');
    $node->appendChild(new Node(2));
    $node->appendChild(new Node(3));

    $fraction = new Fraction(2, 3);
    expect($fraction->node())->toEqual($node);
});

it('can convert to a node without a fraction', function () {
    $node = new Node(5);
    $fraction = new Fraction(5, 1);
    expect($fraction->node())->toEqual($node);
});

it('can simplify fractions', function () {
    $fraction = (new Fraction(2, 4))->simplify();
    expect($fraction)->toEqual(new Fraction(1, 2));
});

it('removes minus in the numerator and the denominator', function () {
    $fraction = (new Fraction(-1, -3))->simplify();
    expect($fraction)->toEqual(new Fraction(1, 3));
});

test('sometimes it cant be simplified', function () {
    $fraction = (new Fraction(3, 4))->simplify();
    expect($fraction)->toEqual(new Fraction(3, 4));
});

it('can multiply fractions', function () {
    $fraction = (new Fraction(2, 3))->multiply(3, 4);
    expect($fraction)->toEqual(new Fraction(1, 2));
});

it('can multiply by a whole number', function () {
    $fraction = (new Fraction(2, 3))->multiply(2);
    expect($fraction)->toEqual(new Fraction(4, 3));
});

it('can divide fractions', function () {
    $fraction = (new Fraction(2, 3))->divide(4, 3);
    expect($fraction)->toEqual(new Fraction(1, 2));
});

it('can divide by a whole number', function () {
    $fraction = (new Fraction(3, 4))->divide(2);
    expect($fraction)->toEqual(new Fraction(3, 8));
});

it('can add fractions', function () {
    $fraction = (new Fraction(1, 2))->add(3, 2);
    expect($fraction)->toEqual(new Fraction(4, 2));
});

it('can add with different denominators', function () {
    $fraction = (new Fraction(2, 3))->add(3, 4);
    expect($fraction)->toEqual(new Fraction(17, 12));
});

it('can add whole numbers', function () {
    $fraction = (new Fraction(3, 7))->add(2);
    expect($fraction)->toEqual(new Fraction(17, 7));
});

it('can subtract fractions', function () {
    $fraction = (new Fraction(2, 3))->subtract(1, 3);
    expect($fraction)->toEqual(new Fraction(1, 3));
});

it('can subtract fractions with different denominators', function () {
    $fraction = (new Fraction(4, 7))->subtract(1, 8);
    expect($fraction)->toEqual(new Fraction(25, 56));
});

it('can get the whole part', function (int $numerator, int $denominator, int $wholePart) {
    $fraction = new Fraction($numerator, $denominator);
    expect($fraction->wholePart())->toBe($wholePart);
})->with([
    [28, 17, 1],
    [99, 4, 24],
    [100, 25, 4],
    [4, 9, 0],
]);

it('can get the fraction part', function (int $numerator, int $denominator, int $fractionPart) {
    $fraction = new Fraction($numerator, $denominator);
    expect($fraction->fractionPart())->toEqual(new Fraction($fractionPart, $denominator));
})->with([
    [28, 17, 11],
    [99, 4, 3],
    [100, 25, 0],
    [4, 9, 4],
]);
