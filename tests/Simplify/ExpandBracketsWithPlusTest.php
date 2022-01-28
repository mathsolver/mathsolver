<?php

use MathSolver\Simplify\ExpandBracketsWithPlus;
use MathSolver\Utilities\StringToTreeConverter;

it('removes single brackets', function () {
    $tree = StringToTreeConverter::run('3(p + 5)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('3p + 3*5'));

    $tree = StringToTreeConverter::run('5(a + b)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5a + 5b'));
});

it('removes double brackets', function () {
    $tree = StringToTreeConverter::run('(x + 3)(x - 5)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x*x - 5x + x*3 - 5*3'));

    $tree = StringToTreeConverter::run('5(a + 7)(b - 9)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5ab + 5*7*b + 5*a*-9 + 5*7*-9'));
});

it('removes with exponents', function () {
    $tree = StringToTreeConverter::run('5(x^2 + y^5)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('5x^2 + 5y^5'));
});

it('removes with more than two sets of brackets', function () {
    $tree = StringToTreeConverter::run('(x + 3)(x - 5)(x - 9)(4 + x)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('4*x*x*x + x*x*x*x + 4*-9*x*x + x*-9*x*x + 4*x*-5*x + x*x*-5*x + 4*-9*-5*x + x*-9*-5*x + 4*x*x*3 + x*x*x*3 + 4*-9*x*3 + x*-9*x*3 + 4*x*-5*3 + x*x*-5*3 + 4*-9*-5*3 + x*-9*-5*3'));

    $tree = StringToTreeConverter::run('(a + 3)(b - 5)(c + 3)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('b*a*c + -5*a*c + b*3*c + -5*3*c + b*a*3 + -5*a*3 + b*3*3 + -5*3*3'));
});

it('removes with more than two terms inside the brackets', function () {
    $tree = StringToTreeConverter::run('(x + y + 9)(x + y)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('x*x + yx + xy + y*y + x*9 + y*9'));

    $tree = StringToTreeConverter::run('(a + b + c + d)(e + f + g + h)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('ea + fa + ga + ha + eb + fb + gb + hb + ec + fc + gc + hc + ed + fd + gd + hd'));
});

it('removes when nested', function () {
    $tree = StringToTreeConverter::run('6(a + 2b) + 2(a + 3b)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('6a + 6*2*b + 2a + 2*3*b'));

    $tree = StringToTreeConverter::run('-4x - 5(7x - 2y)');
    $result = ExpandBracketsWithPlus::run($tree);
    expect($result)->toEqual(StringToTreeConverter::run('-4x + -5*7*x + -5*-2*y'));
});
