<?php

use MathSolver\Utilities\Node;

it('can be instantiated', function () {
    $node = new Node('x');
    expect($node->parent())->toBeNull();
    expect($node->value())->toBe('x');
    expect($node->children())->toBeEmpty();

    $node = new Node('7');
    expect($node->value())->toBe('7');
});

it('can be instantiated from a string', function () {
    $node = new Node('*');
    $node->appendChild(new Node(6));
    $node->appendChild(new Node(4));
    expect(Node::fromString('6 * 4'))->toEqual($node);
});

it('can have a child', function () {
    $node = new Node('+');
    $child = $node->appendChild(new Node('7'));

    expect($node->children())->toHaveCount(1);
    expect($node->children()->first())->toBe($child);
});

it('can have a parent', function () {
    $parent = new Node('-');
    $node = $parent->appendChild(new Node(2));

    expect($node->parent())->toBe($parent);
});

it('can have nested children', function () {
    $root = new Node('+');
    $node = $root->appendChild(new Node('-'));
    $child = $node->appendChild(new Node('y'));

    expect($root->children())->toHaveCount(1);
    expect($root->children()->first())->toBe($node);
    expect($node->children())->toHaveCount(1);
    expect($node->children()->first())->toBe($child);
    expect($child->children())->toHaveCount(0);
});

it('can be nested', function () {
    $root = new Node('+');
    $node = $root->appendChild(new Node('-'));
    $child = $node->appendChild(new Node('y'));

    expect($root->parent())->toBeNull();
    expect($node->parent())->toBe($root);
    expect($child->parent())->toBe($node);
});

it('knows its root node', function () {
    $root = new Node('+');
    $node = $root->appendChild(new Node('-'));
    $child = $node->appendChild(new Node('y'));

    expect($root->root())->toBe($root);
    expect($node->root())->toBe($root);
    expect($child->root())->toBe($root);
});

it('can remove a child', function () {
    $parent = new Node('x');
    $child = $parent->appendChild(new Node(8));
    expect($parent->children())->toHaveCount(1);

    $parent->removeChild($child);
    expect($parent->children())->toHaveCount(0);
});

it('removes only one child', function () {
    $parent = new Node('+');
    $parent->appendChild(new Node(8));
    $child = $parent->appendChild(new Node(8));
    expect($parent->children())->toHaveCount(2);

    $parent->removeChild($child);
    expect($parent->children())->toHaveCount(1);
});

it('can replace a child', function () {
    $root = new Node('x');
    $first = new Node('y');
    $second = new Node('z');
    $third = new Node('a');
    expect($root->children())->toHaveCount(0);

    $root->appendChild($first);
    expect($root->children())->toHaveCount(1);

    $root->appendChild($second);
    expect($root->children())->toHaveCount(2);

    $root->replaceChild($first, $third);
    expect($root->children())->toHaveCount(2);
    expect($third->parent())->toBe($root);
    expect($first->parent())->toBeNull();

    expect($root->children())->toEqual(collect([$third, $second]));
});

it('can sets its children', function () {
    $root = new Node('*');
    $child1 = $root->appendChild(new Node(6));
    $child2 = new Node(8);

    expect($root->children())->toHaveCount(1);
    expect($root->children()->first())->toBe($child1);

    $root->setChildren(collect([$child2]));

    expect($root->children())->toHaveCount(1);
    expect($root->children()->first())->toBe($child2);
    expect($child2->parent())->toBe($root);
});

it('can set its value', function () {
    $node = new Node(8);
    expect($node->value())->not()->toBe('x');

    $node->setValue('x');
    expect($node->value())->toBe('x');
});

it('can get its numeric children', function () {
    $root = new Node('+');
    $numeric = $root->appendChild(new Node(8));
    $nonNumeric = $root->appendChild(new Node('x'));
    $numeric2 = $root->appendChild(new Node(4));

    expect($root->numericChildren())->toEqual(collect([$numeric, $numeric2]));
});

it('can get its non-numeric children', function () {
    $root = new Node('+');
    $numeric = $root->appendChild(new Node(8));
    $nonNumeric = $root->appendChild(new Node('x'));
    $numeric2 = $root->appendChild(new Node(4));

    expect($root->nonNumericChildren())->toEqual(collect([$nonNumeric]));
});

it('can append a child at the top', function () {
    $root = new Node('+');
    $five = $root->appendChild(new Node(5));
    $three = $root->appendChild(new Node(3), $top = true);

    expect($root->children())->toEqual(collect([$three, $five]));
    expect($root->children())->not()->toEqual(collect([$five, $three]));
});

it('can set a parent node', function () {
    $parent = new Node('*');
    $child = new Node(5);
    expect($child->parent())->toBeNull();

    $child->setParent($parent);
    expect($child->parent())->toBe($parent);
});

it('can get the string format', function () {
    $node = new Node(5);
    expect($node->toString())->toBe('5');
});

it('can get the string format when it has children', function () {
    $node = new Node('+');
    $node->appendChild(new Node(5));
    $node->appendChild(new Node('x'));
    expect($node->toString())->toBe('5+x');
});

it('can remove all its children', function () {
    $node = new Node('+');
    $node->appendChild(new Node(5));
    $node->appendChild(new Node('x'));
    expect($node->children())->toHaveCount(2);
    $node->removeAllChildren();
    expect($node->children())->toHaveCount(0);
});
