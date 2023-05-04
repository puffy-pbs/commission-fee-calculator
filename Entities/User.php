<?php

namespace Entities;
use Base\WithdrawsDefinitions;

class User
{
    /** @var int $id */
    public int $id;

    /** @var string $type */
    public string $type;

    /** @var float $totalWithdrawnAmountThisWeek */
    public float $totalWithdrawnAmountThisWeek;

    /** @var float $totalMoneyLeftThisWeek */
    public float $totalMoneyLeftThisWeek;

    /** @var int $totalWithdrawsThisWeek */
    public int $totalWithdrawsThisWeek;

    public function __construct()
    {
        $this->resetValues();
    }

    /**
     * Reset the value to default state
     * @return void
     */
    public function resetValues(): void
    {
        $this->totalWithdrawnAmountThisWeek = 0;
        $this->totalMoneyLeftThisWeek = WithdrawsDefinitions::MAX_WITHDRAW_AMOUNT_WITH_NO_FEES;
        $this->totalWithdrawsThisWeek = 0;
    }

    /**
     * Does user qualify for free transaction ?
     * @return bool
     */
    public function doesUserQualifyForFreeTransaction(): bool
    {
        return $this->totalMoneyLeftThisWeek > 0
            && $this->totalWithdrawsThisWeek <= WithdrawsDefinitions::MAX_ALLOWED_WITHDRAWS_FOR_WEEK;
    }

}
