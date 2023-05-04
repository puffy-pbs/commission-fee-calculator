<?php

namespace Formatters;

use Base\Currency;

final class FormatterProducer
{
    /**
     * Create formatter
     * @param string $currency
     * @return Formatter
     */
    public static function create(string $currency): Formatter
    {
        switch ($currency) {
            case Currency::JPY:
                return new JapaneseYenFormatter();
            default:
                return new DefaultFormatter();
        }
    }
}
