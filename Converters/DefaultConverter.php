<?php

namespace Converters;

use Base\Currency;

class DefaultConverter implements Converter
{

    /**
     * Should convert?
     * @param string $currency
     * @param array $rates
     * @return bool
     */
    private static function shouldConvert(string $currency, array $rates): bool
    {
        return Currency::EUR !== $currency && array_key_exists($currency, $rates);
    }

    /**
     * Convert to euro
     * @param string $currency
     * @param array $rates
     * @param float $amount
     * @return float
     */
    public static function convertToEuro(string $currency, array $rates, float $amount): float
    {
        // If we should not convert, then just return the amount
        if (!self::shouldConvert($currency, $rates)) {
            return $amount;
        }

        // To euro
        $toEuro = $amount / $rates[$currency];

        // JPY does not have cents so we could ceil it
        return $currency === Currency::JPY ? ceil($toEuro) : $toEuro;
    }

    /**
     * Convert from euro
     * @param string $currency
     * @param array $rates
     * @param float $amount
     * @return float
     */
    public static function convertFromEuro(string $currency, array $rates, float $amount): float
    {
        // If we should not convert, then just return the amount
        if (!self::shouldConvert($currency, $rates)) {
            return $amount;
        }

        return $amount * $rates[$currency];
    }
}
