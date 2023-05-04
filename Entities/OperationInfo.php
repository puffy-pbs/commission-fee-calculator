<?php

namespace Entities;

use DateTime;

class OperationInfo
{
    /** @var DateTime $date */
    public DateTime $date;

    /** @var string $operationType */
    public string $operationType;

    /** @var float $amount */
    public float $amount;

    /** @var string $currency */
    public string $currency;
}
