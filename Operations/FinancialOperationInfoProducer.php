<?php

namespace Operations;

use Entities\FinancialOperationInfo;
use Entities\OperationInfo;
use Entities\UserInfo;

final class FinancialOperationInfoProducer
{
    /**
     * Financial operation info create
     * @param OperationInfo $operationInfo
     * @param UserInfo $userInfo
     * @param float $amount
     * @return FinancialOperationInfo
     */
    public static function create(OperationInfo $operationInfo, UserInfo $userInfo, float $amount): FinancialOperationInfo
    {
        return new FinancialOperationInfo($operationInfo->date, $userInfo->user, $userInfo->week, $amount);
    }
}
