<?php

use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;
use MathSolver\Utilities\TreeToStringConverter;

it('can convert a tree to a string', function () {
    $root = new Node('+');
    $root->appendChild(new Node(5));
    $root->appendChild(new Node(3));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('5+3');
});

it('can convert a nested tree', function () {
    $root = new Node('+');
    $times = $root->appendChild(new Node('*'));
    $times->appendChild(new Node(4));
    $times->appendChild(new Node(6));
    $root->appendChild(new Node(5));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('4*6+5');
});

it('can convert with multiple terms', function () {
    $root = new Node('+');
    $root->appendChild(new Node(5));
    $root->appendChild(new Node(8));
    $root->appendChild(new Node(6));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('5+8+6');
});

it('can convert letters', function () {
    $root = new Node('+');
    $root->appendChild(new Node(9));
    $root->appendChild(new Node('x'));
    $root->appendChild(new Node('y'));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('9+x+y');
});

it('can convert minus', function () {
    $root = new Node('+');
    $root->appendChild(new Node(9));
    $root->appendChild(new Node(-8));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('9-8');
});

it('can convert divides', function () {
    $root = new Node('/');
    $root->appendChild(new Node(10));
    $root->appendChild(new Node(2));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('10/2');
});

it('can convert powers', function () {
    $root = new Node('^');
    $root->appendChild(new Node(8));
    $root->appendChild(new Node(2));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('8^2');
});

it('can convert nested operators', function () {
    $root = new Node('+');
    $divide = $root->appendChild(new Node('/'));
    $divide->appendChild(new Node(4));
    $divide->appendChild(new Node(2));
    $times = $root->appendChild(new Node('*'));
    $times->appendChild(new Node(3));
    $power = $times->appendChild(new Node('^'));
    $power->appendChild(new Node(4));
    $power->appendChild(new Node(3));
    $times->appendChild(new Node(6));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('4/2+3*4^3*6');
});

it('can convert brackets', function () {
    $root = new Node('(');
    $plus = $root->appendChild(new Node('+'));
    $plus->appendChild(new Node(8));
    $plus->appendChild(new Node(2));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('(8+2)');
});

it('can convert nested brackets', function () {
    $root = new Node('*');
    $root->appendChild(new Node(3));
    $brackets = $root->appendChild(new Node('('));
    $plus = $brackets->appendChild(new Node('+'));
    $plus->appendChild(new Node(5));
    $plus->appendChild(new Node(1));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('3(5+1)');
});

it('combines products', function () {
    $root = new Node('*');
    $root->appendChild(new Node(4));
    $root->appendChild(new Node('x'));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('4x');
});

it('combines products with multiple letters', function () {
    $root = new Node('*');
    $root->appendChild(new Node(6));
    $root->appendChild(new Node('a'));
    $root->appendChild(new Node('b'));
    $root->appendChild(new Node('c'));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('6abc');
});

it('combines products without a number', function () {
    $root = new Node('*');
    $root->appendChild(new Node('x'));
    $root->appendChild(new Node('y'));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('xy');
});

it('combines products with more than one number', function () {
    $root = new Node('*');
    $root->appendChild(new Node(7));
    $root->appendChild(new Node(3));
    $root->appendChild(new Node('x'));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('7*3x');
});

it('does combine products with nested terms', function () {
    $root = new Node('*');
    $root->appendChild(new Node(7));
    $root->appendChild(new Node('x'));
    $brackets = $root->appendChild(new Node('('));
    $plus = $brackets->appendChild(new Node('+'));
    $plus->appendChild(new Node(2));
    $plus->appendChild(new Node(7));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('7x(2+7)');
});

it('does combine multiplied by zero', function () {
    $root = new Node('*');
    $root->appendChild(new Node(0));
    $root->appendChild(new Node('x'));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('0x');
});

it('can convert functions to a string', function () {
    $sine = new Node('sin');
    $sine->appendChild(new Node(45));
    $result = TreeToStringConverter::run($sine);
    expect($result)->toBe('sin[45]');

    $times = new Node('*');
    $times->appendChild(new Node(2));
    $root = $times->appendChild(new Node('root'));
    $root->appendChild(new Node(16));
    $plus = $root->appendChild(new Node('+'));
    $plus->appendChild(new Node(2));
    $plus->appendChild(new Node(3));
    $result = TreeToStringConverter::run($times);
    expect($result)->toBe('2root[16,2+3]');
});

it('can convert functions to mathjax', function () {
    $sine = new Node('sin');
    $sine->appendChild(new Node(45));
    $result = TreeToStringConverter::run($sine, $mathjax = true);
    expect($result)->toBe('\text{sin}[45]');
});

it('can convert roots to mathjax', function () {
    $times = new Node('*');
    $times->appendChild(new Node(2));
    $root = $times->appendChild(new Node('root'));
    $root->appendChild(new Node(16));
    $plus = $root->appendChild(new Node('+'));
    $plus->appendChild(new Node(2));
    $plus->appendChild(new Node(3));
    $result = TreeToStringConverter::run($times, $mathjax = true);
    expect($result)->toBe('2\sqrt[2+3]{16}');

    // Square root
    $root = new Node('root');
    $root->appendChild(new Node(36));
    $root->appendChild(new Node(2));
    $result = TreeToStringConverter::run($root, $mathjax = true);
    expect($result)->toBe('\sqrt{36}');
});

it('can convert powers to mathjax', function () {
    $power = new Node('^');
    $power->appendChild(new Node(9));
    $power->appendChild(new Node(3));

    $result = TreeToStringConverter::run($power, $mathjax = true);
    expect($result)->toBe('9^{3}');
});

it('replaces -+ by -', function () {
    $power = new Node('+');
    $power->appendChild(new Node(9));
    $power->appendChild(new Node(-3));

    $result = TreeToStringConverter::run($power, $mathjax = true);
    expect($result)->toBe('9-3');
});

it('can convert fractions to mathjax', function () {
    $power = new Node('frac');
    $power->appendChild(new Node(3));
    $power->appendChild(new Node(9));

    $result = TreeToStringConverter::run($power, $mathjax = true);
    expect($result)->toBe('\frac{3}{9}');
});

it('removes times between fractions and letters', function () {
    $times = new Node('*');
    $fraction = $times->appendChild(new Node('frac'));
    $fraction->appendChild(new Node(1));
    $fraction->appendChild(new Node(2));
    $times->appendChild(new Node('y'));

    $result = TreeToStringConverter::run($times, $mathjax = true);
    expect($result)->toBe('\frac{1}{2}y');
});

it('removes the one between a minus and a function', function () {
    $times = new Node('*');
    $times->appendChild(new Node(-1));
    $sine = $times->appendChild(new Node('sin'));
    $sine->appendChild(new Node(45));

    $result = TreeToStringConverter::run($times);
    expect($result)->toBe('-sin[45]');
});

it('removes the one between a minus and a function in mathjax', function () {
    $times = new Node('*');
    $times->appendChild(new Node(-1));
    $root = $times->appendChild(new Node('root'));
    $root->appendChild(new Node(5));
    $root->appendChild(new Node(2));

    $result = TreeToStringConverter::run($times, $mathjax = true);
    expect($result)->toBe('-\sqrt{5}');
});

it('converts to a differentiate function', function () {
    $deriv = new Node('deriv');
    $power = $deriv->appendChild(new Node('^'));
    $power->appendChild(new Node('x'));
    $power->appendChild(new Node(5));

    $result = TreeToStringConverter::run($deriv, $mathjax = true);
    expect($result)->toBe('\frac{d}{dx}[x^{5}]');
});

it('converts to a differentiate function with respect to a variable', function () {
    $deriv = new Node('deriv');
    $power = $deriv->appendChild(new Node('^'));
    $power->appendChild(new Node('y'));
    $power->appendChild(new Node(2));
    $deriv->appendChild(new Node('y'));

    $result = TreeToStringConverter::run($deriv, $mathjax = true);
    expect($result)->toBe('\frac{d}{dy}[y^{2}]');
});

it('removes brackets around exponents', function () {
    $power = new Node('^');
    $power->appendChild(new Node('x'));
    $brackets = $power->appendChild(new Node('('));
    $times = $brackets->appendChild(new Node('*'));
    $times->appendChild(new Node(7));
    $times->appendChild(new Node('x'));

    $result = TreeToStringConverter::run($power, $mathjax = false);
    expect($result)->toBe('x^(7x)');

    $result = TreeToStringConverter::run($power, $mathjax = true);
    expect($result)->toBe('x^{7x}');
});

it('does not remove double brackets around exponents', function () {
    $power = new Node('^');
    $power->appendChild(new Node(2));
    $brackets = $power->appendChild(new Node('('));
    $times = $brackets->appendChild(new Node('*'));

    $leftBrackets = $times->appendChild(new Node('('));
    $leftPlus = $leftBrackets->appendChild(new Node('+'));
    $leftPlus->appendChild(new Node('x'));
    $leftPlus->appendChild(new Node(3));

    $rightBrackets = $times->appendChild(new Node('('));
    $rightPlus = $rightBrackets->appendChild(new Node('+'));
    $rightPlus->appendChild(new Node('x'));
    $rightPlus->appendChild(new Node(-5));

    $result = TreeToStringConverter::run($power, $mathjax = false);
    expect($result)->toBe('2^((x+3)(x-5))');

    $result = TreeToStringConverter::run($power, $mathjax = true);
    expect($result)->toBe('2^{(x+3)(x-5)}');
});

it('converts logarithms with mathjax', function () {
    $log = new Node('log');
    $log->appendChild(new Node(8));
    $log->appendChild(new Node(2));

    $result = TreeToStringConverter::run($log, $mathjax = false);
    expect($result)->toBe('log[8,2]');

    $result = TreeToStringConverter::run($log, $mathjax = true);
    expect($result)->toBe('\log_{2}[8]');
});

it('removes the times symbol between letters and roots', function () {
    $times = new Node('*');
    $times->appendChild(new Node('x'));
    $root = $times->appendChild(new Node('root'));
    $root->appendChild(new Node('y'));
    $root->appendChild(new Node(2));

    $result = TreeToStringConverter::run($times, $mathjax = true);
    expect($result)->toBe('x\sqrt{y}');
});

it('adds brackets around roots inside powers', function () {
    $power = new Node('^');
    $root = $power->appendChild(new Node('root'));
    $root->appendChild(new Node('x'));
    $root->appendChild(new Node(2));
    $power->appendChild(new Node(-1));

    $result = TreeToStringConverter::run($power, $mathjax = true);
    expect($result)->toBe('(\sqrt{x})^{-1}');
});

it('removes times symbol between fractions and brackets', function () {
    $times = new Node('*');
    $frac = $times->appendChild(new Node('frac'));
    $frac->appendChild(new Node(1));
    $frac->appendChild(new Node(2));
    $brackets = $times->appendChild(new Node('('));
    $brackets->appendChild(new Node('x'));

    $result = TreeToStringConverter::run($times, $mathjax = true);
    expect($result)->toBe('\frac{1}{2}(x)');
});

it('adds brackets around multiple powers', function () {
    $root = new Node('^');
    $root->appendChild(new Node('x'));
    $power = $root->appendChild(new Node('^'));
    $power->appendChild(new Node('y'));
    $power->appendChild(new Node('z'));

    $result = TreeToStringConverter::run($root, $mathjax = true);
    expect($result)->toBe('x^{y^{z}}');
});

it('can convert fractions', function () {
    $tree = StringToTreeConverter::run('frac[3, 5]');
    $result = TreeToStringConverter::run($tree, $mathjax = false);
    expect($result)->toBe('3/5');
});

it('can parse non-numeric numerators', function () {
    $tree = StringToTreeConverter::run('frac[x + 3, 2]');
    $result = TreeToStringConverter::run($tree, $mathjax = false);
    expect($result)->toBe('(x+3)/2');
});

it('can parse non-numeric denominators', function () {
    $tree = StringToTreeConverter::run('frac[5, y - 3]');
    $result = TreeToStringConverter::run($tree, $mathjax = false);
    expect($result)->toBe('5/(y-3)');
});
