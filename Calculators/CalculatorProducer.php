<?php

namespace Calculators;

use Base\Fees;
use Base\OperationType;
use Base\UserType;

class CalculatorProducer
{

    /**
     * Create calculator based on parameters
     * @param string $operationType
     * @param string $userType
     * @return Calculator
     */
    public static function create(string $operationType, string $userType): Calculator
    {
        $fee = Fees::DEPOSIT_FEE;
        if ($operationType === OperationType::WITHDRAW) {
            $fee = ($userType === UserType::BUSINESS)
                ? Fees::WITHDRAW_BUSINESS_CLIENT_FEE : Fees::WITHDRAW_PRIVATE_CLIENT_FEE;
        }

        return new DefaultCalculator($fee);
    }
}
