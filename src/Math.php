<?php

namespace MathSolver;

use MathSolver\Utilities\StringToTreeConverter;

class Math
{
    public static function simplify(string $expression): MathObject
    {
        $tree = StringToTreeConverter::run($expression);

        $mathObject = new MathObject($tree);

        return $mathObject->simplify();
    }
}
