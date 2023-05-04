<?php

namespace Entities;

use DateTime;

class FinancialOperationInfo
{
    /** @var DateTime $date */
    public DateTime $date;

    /** @var User $user */
    public User $user;

    /** @var Week $week */
    public Week $week;

    /** @var float $amount */
    public float $amount;

    /**
     * @param DateTime $date
     * @param User $user
     * @param Week $week
     * @param float $amount
     */
    public function __construct(DateTime $date, User $user, Week $week, float $amount)
    {
        $this->date = $date;
        $this->user = $user;
        $this->week = $week;
        $this->amount = $amount;
    }
}
