<?php

namespace Calculators;

abstract class Calculator
{
    /** @var float $fee */
    protected float $fee;

    /**
     * @param float $fee
     */
    public function __construct(float $fee)
    {
        $this->fee = $fee;
    }

    /**
     * @param float $amount
     * @return float
     */
    abstract public function calculateCommission(float $amount): float;
}
