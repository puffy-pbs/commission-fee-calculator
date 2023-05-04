<?php

namespace Operations;

interface FinancialOperation
{
    public function shouldExecute(): bool;

    public function calculateCommission(float $amount): float;
}
