<?php

namespace MathSolver\Utilities;

class Fraction
{
    /**
     * The numerator.
     */
    protected int $numerator;

    /**
     * The denominator.
     */
    protected int $denominator;

    /**
     * Instantiate a new fraction.
     *
     * @return self
     */
    public function __construct(int $numerator, int $denominator = 1)
    {
        $this->numerator = $numerator;
        $this->denominator = $denominator;
    }

    /**
     * Get the numerator.
     */
    public function numerator(): int
    {
        return $this->simplify()->numerator;
    }

    /**
     * Get the denominator.
     */
    public function denominator(): int
    {
        return $this->simplify()->denominator;
    }

    /**
     * Convert this fraction to a node.
     */
    public function node(): Node
    {
        if ($this->denominator === 1) {
            return new Node($this->numerator);
        }

        $node = new Node('frac');
        $node->appendChild(new Node($this->numerator));
        $node->appendChild(new Node($this->denominator));

        return $node;
    }

    /**
     * Simplify the fraction by dividing both the
     * numerator and denominator by the same value.
     *
     * For example: 2/4 => 1/2 and -3/-9 => 1/3.
     */
    public function simplify(): self
    {
        $greatestCommonDivisor = (int) gmp_gcd($this->numerator, $this->denominator);

        if ($this->numerator < 0 && $this->denominator < 0) {
            $greatestCommonDivisor *= -1;
        }

        $numerator = $this->numerator / $greatestCommonDivisor;
        $denominator = $this->denominator / $greatestCommonDivisor;

        if ($numerator >= 0 && $denominator < 0) {
            $numerator *= -1;
            $denominator *= -1;
        }

        return new self($numerator, $denominator);
    }

    /**
     * Multiply this fraction by another fraction or a whole number.
     */
    public function multiply(int $numerator, int $denominator = 1): self
    {
        return (new self($this->numerator * $numerator, $this->denominator * $denominator))->simplify();
    }

    /**
     * Divide this fraction by another fraction or a whole number.
     *
     * Math rule: keep-change-flip. This means that in order to
     * divide by a fraction, you multiply by the reprocipical.
     * For example 1/2 / 3/4 => 1/2 * 4/3.
     */
    public function divide(int $numerator, int $denominator = 1): self
    {
        // Math rule: keep-change-flip
        return $this->multiply($denominator, $numerator);
    }

    /**
     * Add this fraction by another fraction or a whole number.
     *
     * In order to add fractions, you need the same denominators.
     * If the denominators aren't the same, you can use the
     * formula A/B + C/D = (AD + BC)/(BD).
     */
    public function add(int $numerator, int $denominator = 1): self
    {
        if ($denominator === $this->denominator) {
            $this->numerator += $numerator;
            return $this;
        }

        $numerator = ($this->numerator * $denominator) + ($numerator * $this->denominator);
        $denominator = $this->denominator * $denominator;

        return (new self($numerator, $denominator))->simplify();
    }

    /**
     * Subtract this fraction by another fraction or a whole number.
     *
     * To subract, multiply the numerator of one of the fractions
     * by -1, and then add them up.
     */
    public function subtract(int $numerator, int $denominator): self
    {
        return $this->add($numerator * -1, $denominator);
    }

    /**
     * Get the whole part of a fraction.
     *
     * For example, 10/3 = 3 + 1/3, so the whole part here
     * is 3. In 7/4, the whole part is 1.
     */
    public function wholePart(): int
    {
        return floor($this->numerator() / $this->denominator());
    }

    /**
     * Get the not-whole part of a fraction.
     *
     * For example, 10/3 = 3 + 1/3, so the fraction part
     * here is 1/3. In 7/4, the fraction part is 3/4.
     */
    public function fractionPart(): self
    {
        return new self($this->numerator % $this->denominator, $this->denominator);
    }
}
