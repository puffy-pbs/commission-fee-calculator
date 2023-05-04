<?php

namespace Formatters;

class DefaultFormatter implements Formatter
{
    /**
     * Format number
     * @param float $amount
     * @return string
     */
    public function format(float $amount): string
    {
        return number_format(
            round($amount, 2),
            2,
            '.',
            ''
        );
    }
}
