<?php

use MathSolver\Utilities\Node;
use MathSolver\Utilities\StringToTreeConverter;

it('can convert a string to a tree', function () {
    $root = new Node('+');
    $root->appendChild(new Node(5));
    $root->appendChild(new Node('y'));

    $result = StringToTreeConverter::run('5 + y');
    expect($result)->toEqual($root);
});

it('can convert with plus', function () {
    $root = new Node('+');
    $root->appendChild(new Node('a'));
    $root->appendChild(new Node('a'));

    $result = StringToTreeConverter::run('a + a');
    expect($result)->toEqual($root);
});

it('can convert zero to a tree', function () {
    $root = new Node(0);
    $result = StringToTreeConverter::run('0');
    expect($result)->toEqual($root);

    $root = new Node('*');
    $root->appendChild(new Node('0'));
    $root->appendChild(new Node('x'));
    $result = StringToTreeConverter::run('0x');
    expect($result)->toEqual($root);
});

it('can convert with minus', function () {
    $root = new Node('+');
    $root->appendChild(new Node(9));
    $root->appendChild(new Node(-6));

    $result = StringToTreeConverter::run('9 - 6');
    expect($result)->toEqual($root);
});

it('can convert with minus without a number', function () {
    $root = new Node('*');
    $root->appendChild(new Node(-1));
    $root->appendChild(new Node('a'));

    $result = StringToTreeConverter::run('-a');
    expect($result)->toEqual($root);
});

it('can convert with times', function () {
    $root = new Node('*');
    $root->appendChild(new Node(7));
    $root->appendChild(new Node('x'));

    $result = StringToTreeConverter::run('7 * x');
    expect($result)->toEqual($root);
});

it('can convert with times and plus', function () {
    $root = new Node('+');
    $root->appendChild(new Node('x'));
    $times = $root->appendChild(new Node('*'));
    $times->appendChild(new Node('x'));
    $times->appendChild(new Node('x'));

    $result = StringToTreeConverter::run('x+x*x');
    expect($result)->toEqual($root);
});

it('can convert with times and minus', function () {
    $root = new Node('*');
    $root->appendChild(new Node(7));
    $root->appendChild(new Node(-9));

    $result = StringToTreeConverter::run('7 * -9');
    expect($result)->toEqual($root);
});

it('can convert with multiple times and plus', function () {
    $root = new Node('+');
    $root->appendChild(new Node('x'));
    $times = $root->appendChild(new Node('*'));
    $times->appendChild(new Node(4));
    $times->appendChild(new Node(3));
    $times->appendChild(new Node(9));
    $root->appendChild(new Node('y'));

    $result = StringToTreeConverter::run('x + 4 * 3 * 9 + y');
    expect($result)->toEqual($root);
});

it('can convert with divide', function () {
    $root = new Node('/');
    $root->appendChild(new Node(8));
    $root->appendChild(new Node(2));

    $result = StringToTreeConverter::run('8 / 2');
    expect($result)->toEqual($root);
});

it('can convert with divide and minus', function () {
    $root = new Node('/');
    $root->appendChild(new Node(-8));
    $root->appendChild(new Node(2));

    $result = StringToTreeConverter::run('-8 / 2');
    expect($result)->toEqual($root);
});

it('can convert with multiple operators', function () {
    $root = new Node('+');
    $root->appendChild(new Node(5));
    $divide = $root->appendChild(new Node('/'));
    $divide->appendChild(new Node(-6));
    $divide->appendChild(new Node(2));
    $times = $root->appendChild(new Node('*'));
    $times->appendChild(new Node(3));
    $times->appendChild(new Node(4));

    $result = StringToTreeConverter::run('5 + -6 / 2 + 3 * 4');
    expect($result)->toEqual($root);
});

it('can convert with power', function () {
    $root = new Node('^');
    $root->appendChild(new Node(8));
    $root->appendChild(new Node(2));

    $result = StringToTreeConverter::run('8^2');
    expect($result)->toEqual($root);
});

it('can convert with a power of zero', function () {
    $root = new Node('^');
    $root->appendChild(new Node(8));
    $root->appendChild(new Node(0));

    $result = StringToTreeConverter::run('8^0');
    expect($result)->toEqual($root);
});

it('can convert with nested powers', function () {
    $root = new Node('+');
    $root->appendChild(new Node(4));
    $times = $root->appendChild(new Node('*'));
    $power = $times->appendChild(new Node('^'));
    $power->appendChild(new Node(7));
    $power->appendChild(new Node(3));
    $times->appendChild(new Node(9));

    $result = StringToTreeConverter::run('4 + 7^3 * 9');
    expect($result)->toEqual($root);
});

it('can convert with brackets', function () {
    $root = new Node('*');
    $root->appendChild(new Node(3));
    $brackets = $root->appendChild(new Node('('));
    $plus = $brackets->appendChild(new Node('+'));
    $plus->appendChild(new Node(3));
    $plus->appendChild(new Node(4));

    $result = StringToTreeConverter::run('3 * (3 + 4)');
    expect($result)->toEqual($root);
});

it('can convert brackets without a multiplication sign', function () {
    $root = new Node('*');
    $root->appendChild(new Node(6));
    $brackets = $root->appendChild(new Node('('));
    $plus = $brackets->appendChild(new Node('+'));
    $plus->appendChild(new Node('x'));
    $plus->appendChild(new Node(4));

    $result = StringToTreeConverter::run('6(x + 4)');
    expect($result)->toEqual($root);
});

it('can convert with complex brackets', function () {
    $root = new Node('+');

    $times1 = $root->appendChild(new Node('*'));
    $times1->appendChild(new Node(-3));
    $brackets1 = $times1->appendChild(new Node('('));
    $nestedPlus1 = $brackets1->appendChild(new Node('+'));
    $nestedPlus1->appendChild(new Node('x'));
    $nestedTimes1 = $nestedPlus1->appendChild(new Node('*'));
    $nestedTimes1->appendChild(new Node(-4));
    $nestedTimes1->appendChild(new Node('y'));

    $times2 = $root->appendChild(new Node('*'));
    $times2->appendChild(new Node(-1));
    $brackets2 = $times2->appendChild(new Node('('));
    $nestedPlus2 = $brackets2->appendChild(new Node('+'));
    $nestedPlus2->appendChild(new Node('x'));
    $nestedTimes2 = $nestedPlus2->appendChild(new Node('*'));
    $nestedTimes2->appendChild(new Node(-2));
    $nestedTimes2->appendChild(new Node('y'));

    $times3 = $root->appendChild(new Node('*'));
    $times3->appendChild(new Node(-13));
    $times3->appendChild(new Node('y'));

    $result = StringToTreeConverter::run('-3(x - 4y) - (x - 2y) - 13y');
    expect($result)->toEqual($root);
});

it('can convert with nested brackets', function () {
    $root = new Node('/');
    $root->appendChild(new Node(2));
    $outerBrackets = $root->appendChild(new Node('('));
    $outerPlus = $outerBrackets->appendChild(new Node('+'));
    $outerPlus->appendChild(new Node(5));
    $innerBrackets = $outerPlus->appendChild(new Node('('));
    $innerPlus = $innerBrackets->appendChild(new Node('+'));
    $innerPlus->appendChild(new Node(7));
    $innerPlus->appendChild(new Node(6));

    $result = StringToTreeConverter::run('2 / (5 + (7 + 6))');
    expect($result)->toEqual($root);
});

it('converts with double brackets', function () {
    $root = new Node('*');
    $brackets1 = $root->appendChild(new Node('('));
    $plus1 = $brackets1->appendChild(new Node('+'));
    $plus1->appendChild(new Node('p'));
    $plus1->appendChild(new Node(3));
    $brackets2 = $root->appendChild(new Node('('));
    $plus2 = $brackets2->appendChild(new Node('+'));
    $plus2->appendChild(new Node('p'));
    $plus2->appendChild(new Node(-5));

    $result = StringToTreeConverter::run('(p + 3)(p - 5)');
    expect($result)->toEqual($root);
});

it('can convert terms with multiply', function () {
    $root = new Node('*');
    $root->appendChild(new Node(7));
    $root->appendChild(new Node('x'));
    $root->appendChild(new Node('y'));
    $result = StringToTreeConverter::run('7xy');
    expect($result)->toEqual($root);

    $root = new Node('*');
    $root->appendChild(new Node(-82));
    $root->appendChild(new Node('a'));
    $root->appendChild(new Node('b'));
    $root->appendChild(new Node('c'));
    $result = StringToTreeConverter::run('-82abc');
    expect($result)->toEqual($root);

    $root = new Node('*');
    $root->appendChild(new Node(7.5));
    $root->appendChild(new Node('a'));
    $result = StringToTreeConverter::run('7.5a');
    expect($result)->toEqual($root);

    $root = new Node('*');
    $root->appendChild(new Node('a'));
    $root->appendChild(new Node('b'));
    $result = StringToTreeConverter::run('ab');
    expect($result)->toEqual($root);

    $root = new Node('*');
    $root->appendChild(new Node(10));
    $root->appendChild(new Node('u'));
    $root->appendChild(new Node(5));
    $root->appendChild(new Node('y'));
    $result = StringToTreeConverter::run('10u * 5y');
    expect($result)->toEqual($root);

    $root = new Node('+');
    $times = $root->appendChild(new Node('*'));
    $times->appendChild(new Node(3));
    $times->appendChild(new Node('x'));
    $root->appendChild(new Node(4));
    $result = StringToTreeConverter::run('3x + 4');
    expect($result)->toEqual($root);
});

it('can parse functions into their own nodes', function () {
    $root = new Node('root');
    $root->appendChild(new Node(9));
    $result = StringToTreeConverter::run('root(9)');
    expect($result)->toEqual($root);

    $tangent = new Node('tan');
    $tangent->appendChild(new Node(45));
    $result = StringToTreeConverter::run('tan(45)');
    expect($result)->toEqual($tangent);
});

it('can parse functions with other operations', function () {
    $plus = new Node('+');
    $times = $plus->appendChild(new Node('*'));
    $plus->appendChild(new Node(3));
    $times->appendChild(new Node(2));
    $root = $times->appendChild(new Node('root'));
    $root->appendChild(new Node(9));
    $result = StringToTreeConverter::run('2 * root(9) + 3');
    expect($result)->toEqual($plus);

    $times = new Node('*');
    $times->appendChild(new Node(5));
    $sine = $times->appendChild(new Node('sin'));
    $sine->appendChild(new Node(90));
    $result = StringToTreeConverter::run('5sin(90)');
    expect($result)->toEqual($times);
});

it('can parse functions with brackets outside', function () {
    $times = new Node('*');
    $tangent = $times->appendChild(new Node('tan'));
    $tangent->appendChild(new Node(45));
    $brackets = $times->appendChild(new Node('('));
    $plus = $brackets->appendChild(new Node('+'));
    $plus->appendChild(new Node('3'));
    $plus->appendChild(new Node('x'));

    $result = StringToTreeConverter::run('tan(45) * (3 + x)');
    expect($result)->toEqual($times);

    $result = StringToTreeConverter::run('tan(45)(3 + x)');
    expect($result)->toEqual($times);

    $times = new Node('*');
    $brackets = $times->appendChild(new Node('('));
    $plus = $brackets->appendChild(new Node('+'));
    $plus->appendChild(new Node('3'));
    $plus->appendChild(new Node('x'));
    $tangent = $times->appendChild(new Node('tan'));
    $tangent->appendChild(new Node(45));

    $result = StringToTreeConverter::run('(3 + x)tan(45)');
    expect($result)->toEqual($times);
});

it('can parse functions with brackets inside', function () {
    $sine = new Node('sin');
    $plus = $sine->appendChild(new Node('+'));
    $plus->appendChild(new Node(90));
    $times = $plus->appendChild(new Node('*'));
    $times->appendChild(new Node(3));
    $brackets = $times->appendChild(new Node('('));
    $innerPlus = $brackets->appendChild(new Node('+'));
    $innerPlus->appendChild(new Node('x'));
    $innerPlus->appendChild(new Node(5));

    $result = StringToTreeConverter::run('sin(90 + 3(x + 5))');
    expect($result)->toEqual($sine);
});

it('can parse functions with multiple parameters', function () {
    $root = new Node('root');
    $root->appendChild(new Node(16));
    $root->appendChild(new Node(2));
    $result = StringToTreeConverter::run('root(16, 2)');
    expect($result)->toEqual($root);

    $times = new Node('*');
    $times->appendChild(new Node(2));
    $root = $times->appendChild(new Node('root'));
    $root->appendChild(new Node(16));
    $plus = $root->appendChild(new Node('+'));
    $plus->appendChild(new Node(2));
    $plus->appendChild(new Node(3));
    $result = StringToTreeConverter::run('2root(16, 2 + 3)');
    expect($result)->toEqual($times);
});

it('can parse functions multiplied by a letter', function () {
    $times = new Node('*');
    $times->appendChild(new Node('x'));
    $root = $times->appendChild(new Node('root'));
    $root->appendChild(new Node(16));
    $root->appendChild(new Node(2));

    $result = StringToTreeConverter::run('xroot(16, 2)');
    expect($result)->toEqual($times);
});

it('can parse fractions', function () {
    $fraction = new Node('frac');
    $fraction->appendChild(new Node(3));
    $fraction->appendChild(new Node(8));

    $result = StringToTreeConverter::run('frac(3, 8)');
    expect($result)->toEqual($fraction);
});

it('can parse equations', function () {
    $equal = new Node('=');
    $equal->appendChild(new Node('x'));
    $equal->appendChild(new Node(3));

    $result = StringToTreeConverter::run('x = 3');
    expect($result)->toEqual($equal);
});
