<?php

use MathSolver\Exponents\AppendPowersToBrackets;
use MathSolver\Utilities\StringToTreeConverter;

it('appends powers to multiplications inside brackets', function () {
    $tree = StringToTreeConverter::run('(2x)^2');
    $result = AppendPowersToBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('2^2 * x^2'));
});

it('adds brackets if there is a power inside the brackets', function () {
    $tree = StringToTreeConverter::run('(3y^2)^3');
    $result = AppendPowersToBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3^3 * (y^2)^3'));
});

it('does not append powers if there is a plus inside the brackets', function () {
    $tree = StringToTreeConverter::run('(3y + 7)^3');
    $result = AppendPowersToBrackets::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('(3y + 7)^3'));
});
