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
    expect(Math::from('7x + 5x')->withSteps()->simplify()->string())->toEqual([
        'result' => '12x',
        'steps' => collect([['type' => 'simplify', 'name' => 'Add like terms', 'result' => '12x']]),
    ]);

    expect(Math::from('7x + 5x')->withSteps()->simplify()->mathjax())->toEqual([
        'result' => '12x',
        'steps' => collect([['type' => 'simplify', 'name' => 'Add like terms', 'result' => '12x']]),
    ]);
});

it('can substitute', function () {
    expect(Math::from('x + y')->substitute(['x' => '3', 'y' => '5'])->simplify()->string())->toBe('8');
});

it('records steps for substitution', function () {
    expect(Math::from('x + y')->withSteps()->substitute(['x' => '3', 'y' => '5'])->simplify()->string())->toEqual([
        'result' => '8',
        'steps' => collect([
            ['type' => 'substitute', 'name' => 'Substitute \( x \) for \( 3 \) and \( y \) for \( 5 \)', 'result' => '(3)+(5)'],
            ['type' => 'simplify', 'name' => 'Remove brackets', 'result' => '3+5'],
            ['type' => 'simplify', 'name' => 'Add real numbers', 'result' => '8'],
        ]),
    ]);
});

it('can solve equations', function () {
    expect(Math::from('2x = 16')->solveFor('x')->mathjax())->toBe('8');
});
