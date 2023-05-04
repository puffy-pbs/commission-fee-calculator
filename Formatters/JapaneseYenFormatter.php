<?php

namespace Formatters;

class JapaneseYenFormatter implements Formatter
{
    /**
     * JPY values does not have decimal points so just ceil it
     * @param float $amount
     * @return string
     */
    public function format(float $amount): string
    {
        return strval(ceil($amount));
    }
}
