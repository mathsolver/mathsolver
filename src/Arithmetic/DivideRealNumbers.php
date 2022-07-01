<?php

namespace MathSolver\Arithmetic;

use MathSolver\Utilities\Node;
use MathSolver\Utilities\Step;

class DivideRealNumbers extends Step
{
    public static ?string $docs = <<<'MARKDOWN'
        Numbers can be divided:

        $$ 16 / 8 = 2 $$

        Often this leads to an approximation of the answer:

        $$ 34 / 3 \approx 11.3333333 $$

        You can round the number as the second argument of the \\( \text{calc} \\) function:

        $$ \text{calc}[34 / 3, \boldsymbol 2] = 11.33 $$
        MARKDOWN;

    /**
     * Divide the numbers.
     */
    public function handle(Node $node): Node
    {
        $quotient = $node->child(0)->value() / $node->child(1)->value();

        return new Node($quotient);
    }

    /**
     * Only run this function when it is in a `calc` function.
     */
    public function shouldRun(Node $node): bool
    {
        return $node->value() === 'frac'
            && $node->isChildOf('calc')
            && $node->child(0)->isNumeric()
            && $node->child(1)->isNumeric();
    }
}
