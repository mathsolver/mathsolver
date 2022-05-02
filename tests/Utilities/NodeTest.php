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
    expect($node->child(0))->toBe($child);
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
    expect($root->child(0))->toBe($node);
    expect($node->children())->toHaveCount(1);
    expect($node->child(0))->toBe($child);
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
    expect($root->child(0))->toBe($child1);

    $root->setChildren(collect([$child2]));

    expect($root->children())->toHaveCount(1);
    expect($root->child(0))->toBe($child2);
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

it('can get its nth child', function () {
    $node = new Node('+');
    $firstChild = $node->appendChild(new Node(3));
    $secondChild = $node->appendChild(new Node(5));

    expect($node->child(0))->toBe($firstChild);
    expect($node->child())->toBe($firstChild);
    expect($node->child(1))->toBe($secondChild);
    expect($node->child(-1))->toBe($secondChild);
});

it('returns null if the nth child does not exist', function () {
    $node = new Node('+');
    $node->appendChild(new Node(3));
    $node->appendChild(new Node(5));
    expect($node->child(2))->toBeNull();
});

it('knows if its value is numeric', function (string $value, bool $isNumeric) {
    $node = new Node($value);
    expect($node->isNumeric())->toBe($isNumeric);
})->with([
    [4, true],
    ['x', false],
    ['*', false],
    ['π', true],
    [0.125, true],
    ['28', true],
]);

it('knows if fractions are numeric', function () {
    // numeric fraction
    $fraction = new Node('frac');
    $fraction->appendChild(new Node(2));
    $fraction->appendChild(new Node(5));
    expect($fraction->isNumeric())->toBeTrue();

    // non-numeric fraction
    $fraction = new Node('frac');
    $fraction->appendChild(new Node(2));
    $fraction->appendChild(new Node('x'));
    expect($fraction->isNumeric())->toBeFalse();
});

it('knows if itself or its children contain a value', function () {
    // root level
    $node = new Node('x');
    expect($node->contains('x'))->toBeTrue();

    $node = new Node(5);
    expect($node->contains('x'))->toBeFalse();

    // first children
    $node = new Node('*');
    $node->appendChild(new Node(3));
    $node->appendChild(new Node('y'));
    expect($node->contains('x'))->toBeFalse();
    expect($node->contains('y'))->toBeTrue();

    // deeper children
    $node = new Node('*');
    $power = $node->appendChild(new Node('^'));
    $power->appendChild(new Node('x'));
    $power->appendChild(new Node(7));
    expect($node->contains('x'))->toBeTrue();
    expect($node->contains('y'))->toBeFalse();
});

it('resets the indexes of its children', function () {
    $root = new Node('*');
    $constant = $root->appendChild(new Node(2));
    $variable = $root->appendChild(new Node('x'));

    $root->removeChild($constant);
    expect(array_key_exists(0, $root->children()->toArray()))->toBeTrue();
    expect($root->child(0))->toBe($variable);
});
