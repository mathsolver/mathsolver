<?php

use MathSolver\Math;
use MathSolver\Utilities\Node;

it('can simplify expressions', function ($input, $output) {
    $result = (string) Math::from($input)->simplify();
    expect($result)->toBe(str_replace(' ', '', $output));
})->with([
    ['5x * 4', '20x'],
    ['7x + 5x', '12x'],
    ['2a + 7b + 3a + 4b', '5a + 11b'],
]);

it('can output a string', function () {
    expect(Math::from('3 + 5')->string())->toBe('3+5');
});

it('can output a tree', function () {
    $plus = new Node('+');
    $plus->appendChild(new Node(3));
    $plus->appendChild(new Node(5));

    expect(Math::from('3 + 5')->tree())->toEqual($plus);
});

it('can format mathjax output', function () {
    expect(Math::from('root(25, 2)')->mathjax())->toBe('\sqrt{25}');
});

it('can return steps', function () {
    expect(Math::from('7x + 5x')->withSteps()->simplify()->string())->toBe([
        'result' => '12x',
        'steps' => [['name' => 'Add like terms', 'result' => '12x']],
    ]);

    expect(Math::from('7x + 5x')->withSteps()->simplify()->mathjax())->toBe([
        'result' => '12x',
        'steps' => [['name' => 'Add like terms', 'result' => '12x']],
    ]);
});

it('can substitute', function () {
    expect(Math::from('x + y')->substitute(['x' => '3', 'y' => '5'])->simplify()->string())->toBe('8');
});
