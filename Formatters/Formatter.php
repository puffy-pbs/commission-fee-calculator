<?php

namespace Formatters;

interface Formatter
{
    public function format(float $amount): string;
}
