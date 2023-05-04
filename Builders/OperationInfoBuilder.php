<?php

namespace Builders;

use DateTime;
use Entities\OperationInfo;
use InvalidArgumentException;

final class OperationInfoBuilder implements Builder
{
    /** @var OperationInfo $operationInfo */
    private OperationInfo $operationInfo;

    public function __construct()
    {
        $this->operationInfo = new OperationInfo();
    }

    /**
     * Set date
     * @param string $date
     * @return $this
     */
    public function setDate(string $date): OperationInfoBuilder
    {
        $dateTimeObj = DateTime::createFromFormat('Y-m-d', $date);
        if (false === $dateTimeObj) {
            throw new InvalidArgumentException('Invalid date');
        }

        $this->operationInfo->date = $dateTimeObj->setTime(0, 0);
        return $this;
    }

    /**
     * Set operation type
     * @param string $operationType
     * @return $this
     */
    public function setOperationType(string $operationType): OperationInfoBuilder
    {
        $this->operationInfo->operationType = $operationType;
        return $this;
    }

    /**
     * Set amount
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount): OperationInfoBuilder
    {
        $this->operationInfo->amount = $amount;
        return $this;
    }

    /**
     * Set currency
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): OperationInfoBuilder
    {
        $this->operationInfo->currency = $currency;
        return $this;
    }

    /**
     * Build
     * @return OperationInfo
     */
    public function build(): OperationInfo
    {
        return $this->operationInfo;
    }
}
