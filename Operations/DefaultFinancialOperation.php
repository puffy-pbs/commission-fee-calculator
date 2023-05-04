<?php

namespace Operations;

use Calculators\Calculator;

class DefaultFinancialOperation implements FinancialOperation
{
    /** @var Calculator $calculator */
    protected Calculator $calculator;

    /**
     * @param Calculator $calculator
     */
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Should do preliminary job
     * @return bool
     */
    public function shouldExecute(): bool
    {
        return false;
    }

    /**
     * Calculate commission
     * @param float $amount
     * @return float
     */
    public function calculateCommission(float $amount): float
    {
        return $this->calculator->calculateCommission($amount);
    }


}
