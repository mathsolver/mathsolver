<?php

use MathSolver\Utilities\PrimeFactorer;

it('can find prime factors of an integer', function ($input, $output) {
    expect(PrimeFactorer::run($input))->toBe($output);
})->with([
    [1, []],
    [2, [2]],
    [3, [3]],
    [4, [2, 2]],
    [5, [5]],
    [6, [2, 3]],
    [7, [7]],
    [8, [2, 2, 2]],
    [9, [3, 3]],
    [10, [2, 5]],
    [11, [11]],
    [12, [2, 2, 3]],
    [13, [13]],
    [14, [2, 7]],
    [15, [3, 5]],
    [16, [2, 2, 2, 2]],
    [17, [17]],
    [18, [2, 3, 3]],
    [19, [19]],
    [20, [2, 2, 5]],
]);

it('can find prime factors of large numbers', function ($input, $output) {
    expect(PrimeFactorer::run($input))->toBe($output);
})->with([
    [53, [53]],
    [102, [2, 3, 17]],
    [345, [3, 5, 23]],
    [625, [5, 5, 5, 5]],
    [800, [2, 2, 2, 2, 2, 5, 5]],
    [1154, [2, 577]],
    [2204, [2, 2, 19, 29]],
    [5294, [2, 2647]],
    [6912, [2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3]],
    [9240, [2, 2, 2, 3, 5, 7, 11]],
]);
