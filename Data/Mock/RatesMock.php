<?php

namespace Data\Mock;

use Base\Currency;

class RatesMock
{

    /**
     * Rates mock
     * @return array
     */
    public static function getRates(): array
    {
        return [
            Currency::EUR => 1,
            Currency::USD => 1.1497,
            Currency::JPY => 129.53,
        ];
    }
}
