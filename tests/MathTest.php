<?php

use MathSolver\Math;

it('can simplify expressions', function ($input, $output) {
    $result = (string) Math::from($input)->simplify();
    expect($result)->toBe(str_replace(' ', '', $output));
})->with([
    ['5x * 4', '20x'],
    ['7x + 5x', '12x'],
    ['2a + 7b + 3a + 4b', '5a + 11b'],
]);
