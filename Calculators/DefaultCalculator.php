<?php

namespace Calculators;

class DefaultCalculator extends Calculator
{
    /**
     * Calculate commission value
     * @param float $amount
     * @return float
     */
    public function calculateCommission(float $amount): float
    {
        return $amount * ($this->fee / 100);
    }
}
