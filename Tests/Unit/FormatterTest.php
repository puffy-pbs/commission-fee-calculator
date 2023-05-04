<?php

namespace Tests\Unit;

use Base\Currency;
use Formatters\FormatterProducer;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    /** @var float AMOUNT_EUR */
    private const AMOUNT_EUR = 5.6;

    /** @var float AMOUNT_JPY */
    private const AMOUNT_JPY = 5.66;

    /**
     * On EUR formatting I receive a value with two digits after decimal separator
     * @return void
     */
    public function testCaseOnFormatEURIReceiveTwoDigitsAfterTheDecimalSeparator(): void
    {
        // Create formatter
        $formatter = FormatterProducer::create(Currency::EUR);

        // Amount
        $amount = $formatter->format(self::AMOUNT_EUR);

        // Get digits count
        preg_match('/(?<=\.)\d+$/', $amount, $digitsAfterTheDecimalSeparatorCount);

        // Assert
        $this->assertTrue(2 == strlen($digitsAfterTheDecimalSeparatorCount[0]));
    }

    /**
     * On EUR formatting I receive a value cast to the nearest int
     * @return void
     */
    public function testCaseOnFormatJPYIReceiveWholeNumber(): void
    {
        $formatter = FormatterProducer::create(Currency::JPY);
        $amount = $formatter->format(self::AMOUNT_JPY);

        $this->assertTrue(1 == strlen($amount));
    }
}
