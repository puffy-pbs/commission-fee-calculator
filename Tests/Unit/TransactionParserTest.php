<?php

namespace Tests\Unit;

use Parsers\OperationInfoParsedData;
use Parsers\OperationInfoParser;
use PHPUnit\Framework\TestCase;

class TransactionParserTest extends TestCase
{
    /** @var array[] TRANSACTIONS_ARRAY */
    private const TRANSACTIONS_ARRAY = [
        ['2014-12-31', '4', 'private', 'withdraw', 1200.00, 'EUR'],
        ['2015-01-01', '4', 'private', 'withdraw', 1000.00, 'EUR'],
    ];

    /** @var OperationInfoParser $transactionParser */
    private OperationInfoParser $transactionParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionParser = new OperationInfoParser();
    }

    /**
     * Parse data
     * @return void
     */
    public function testParseData()
    {
        foreach (self::TRANSACTIONS_ARRAY as $transaction) {
            $parsedData = $this->transactionParser->parse($transaction);
            $this->assertInstanceOf(OperationInfoParsedData::class, $parsedData);
        }
    }
}
