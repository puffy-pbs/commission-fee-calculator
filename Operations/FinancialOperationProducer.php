<?php

namespace Operations;

use Base\OperationType;
use Base\UserType;
use Calculators\CalculatorProducer;

final class FinancialOperationProducer
{
    /**
     * Create financial operation
     * @param string $operationType
     * @param string $userType
     * @return FinancialOperation
     */
    public static function create(string $operationType, string $userType): FinancialOperation
    {
        // Create calculator
        $calculator = CalculatorProducer::create($operationType, $userType);

        switch (true) {
            case $operationType === OperationType::WITHDRAW && $userType === UserType::PRIVATE:
                return new PrivateClientWithdrawFinancialOperation($calculator);
            default:
                return new DefaultFinancialOperation($calculator);
        }
    }
}
