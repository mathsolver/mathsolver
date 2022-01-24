<?php

use MathSolver\Utilities\Node;
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
    expect($result)->toBe('9+-8');
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
    expect($result)->toBe('3*(5+1)');
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

it('does not combine products with more than one number', function () {
    $root = new Node('*');
    $root->appendChild(new Node(7));
    $root->appendChild(new Node(3));
    $root->appendChild(new Node('x'));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('7*3*x');
});

it('does not combine products with nested terms', function () {
    $root = new Node('*');
    $root->appendChild(new Node(7));
    $root->appendChild(new Node('x'));
    $brackets = $root->appendChild(new Node('('));
    $plus = $brackets->appendChild(new Node('+'));
    $plus->appendChild(new Node(2));
    $plus->appendChild(new Node(7));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('7*x*(2+7)');
});

it('does combine multiplied by zero', function () {
    $root = new Node('*');
    $root->appendChild(new Node('x'));
    $root->appendChild(new Node(0));

    $result = TreeToStringConverter::run($root);
    expect($result)->toBe('0x');
});

it('can convert functions to a string', function () {
    $sine = new Node('sin');
    $sine->appendChild(new Node(45));
    $result = TreeToStringConverter::run($sine);
    expect($result)->toBe('sin(45)');

    $times = new Node('*');
    $times->appendChild(new Node(2));
    $root = $times->appendChild(new Node('root'));
    $root->appendChild(new Node(16));
    $plus = $root->appendChild(new Node('+'));
    $plus->appendChild(new Node(2));
    $plus->appendChild(new Node(3));
    $result = TreeToStringConverter::run($times);
    expect($result)->toBe('2*root(16,2+3)');
});
