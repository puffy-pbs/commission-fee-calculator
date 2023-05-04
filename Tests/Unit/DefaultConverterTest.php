<?php

namespace Tests\Unit;

use Base\Currency;
use Converters\DefaultConverter;
use PHPUnit\Framework\TestCase;

class DefaultConverterTest extends TestCase
{
    /** @var float DEFAULT_AMOUNT */
    private const DEFAULT_AMOUNT = 5.6;

    /** @var array $rates */
    private array $rates;

    protected function setUp(): void
    {
        $this->rates = [
            Currency::EUR => 1,
            Currency::USD => 1.1497,
            Currency::JPY => 129.53,
        ];
    }

    /**
     * Conversion from euro to euro should not have a difference
     * @return void
     */
    public function testConvertEuroToEuroAndIReceiveTheSameResult(): void
    {
        // Convert
        $convertedAmount = DefaultConverter::convertToEuro(
            Currency::EUR,
            $this->rates,
            self::DEFAULT_AMOUNT
        );

        // Assert
        $this->assertSame(self::DEFAULT_AMOUNT, $convertedAmount);
    }

    /**
     * Can convert JPY to EUR
     * @return void
     */
    public function testConvertJPYToEuro(): void
    {
        // Convert
        $convertedAmount = DefaultConverter::convertToEuro(
            Currency::JPY,
            $this->rates,
            self::DEFAULT_AMOUNT
        );

        // Assert
        $expectedAmount = ceil(self::DEFAULT_AMOUNT / $this->rates[Currency::JPY]);
        $this->assertSame($expectedAmount, $convertedAmount);
    }

    /**
     * Conversion from euro to euro should not have a difference
     * @return void
     */
    public function testConvertFromEuroToEuroAndIReceiveTheSameResult(): void
    {
        // Convert
        $convertedAmount = DefaultConverter::convertFromEuro(
            Currency::EUR,
            $this->rates,
            self::DEFAULT_AMOUNT
        );

        // Assert
        $this->assertSame(self::DEFAULT_AMOUNT, $convertedAmount);
    }

    /**
     * Can convert from EUR to JPY
     * @return void
     */
    public function testConvertFromEuroToJPY(): void
    {
        // Convert
        $convertedAmount = DefaultConverter::convertFromEuro(
            Currency::JPY,
            $this->rates,
            self::DEFAULT_AMOUNT
        );

        // Assert
        $expectedAmount = self::DEFAULT_AMOUNT * $this->rates[Currency::JPY];
        $this->assertSame($expectedAmount, $convertedAmount);
    }
}
