<?php

use MathSolver\Functions\GenerateRandomNumber;
use MathSolver\Utilities\StringToTreeConverter;
use MathSolver\Utilities\TreeToStringConverter;

it('can generate a random number', function () {
    $tree = StringToTreeConverter::run('rand[1, 10]');
    $result = GenerateRandomNumber::run($tree);

    expect((int) TreeToStringConverter::run($result))
        ->toBeNumeric()
        ->toBeGreaterThanOrEqual(1)
        ->toBeLessThanOrEqual(10);

    $tree = StringToTreeConverter::run('rand[100, 1000]');
    $result = GenerateRandomNumber::run($tree);

    expect((int) TreeToStringConverter::run($result))
        ->toBeNumeric()
        ->toBeGreaterThanOrEqual(100)
        ->toBeLessThanOrEqual(1000);
});

it('never falls out of the range', function () {
    $tree = StringToTreeConverter::run('rand[1, 10]');

    for ($i = 1; $i <= 1000; $i++) {
        $result = GenerateRandomNumber::run($tree);

        expect((int) TreeToStringConverter::run($result))
            ->toBeGreaterThanOrEqual(1)
            ->toBeLessThanOrEqual(10);
    }
});

it('is spreaded approximately evenly', function () {
    $tree = StringToTreeConverter::run('rand[1, 10]');

    $results = [];
    for ($i = 1; $i <= 10000; $i++) {
        $results[] = (int) TreeToStringConverter::run(GenerateRandomNumber::run($tree));
    }

    expect(array_count_values($results))->each(
        fn ($number) => $number->toBeGreaterThan(900)->toBeLessThan(1100)
    );
});
