<?php

namespace Converters;

interface Converter
{
    /** Convert to euro
     * @param string $currency
     * @param array $rates
     * @param float $amount
     * @return float
     */
    public static function convertToEuro(string $currency, array $rates, float $amount): float;

    /**
     * Convert from euro
     * @param string $currency
     * @param array $rates
     * @param float $amount
     * @return float
     */
    public static function convertFromEuro(string $currency, array $rates, float $amount): float;
}
