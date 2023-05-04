<?php

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Readers\CSVFileReader;

class CsvFileReaderTest extends TestCase
{
    /** @var string NON_EXISTING_FILENAME */
    private const NON_EXISTENT_FILENAME = 'iDoNotExist.txt';

    /** @var string EXISTING_FILENAME */
    private const EXISTING_FILENAME = 'Tests/Mock/Data/exceeded_amount_initially.csv';

    /**
     * Expect exception on not existing file
     * @return void
     */
    public function testThrowExceptionOnNonExistentFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CSVFileReader(self::NON_EXISTENT_FILENAME);
    }

    /**
     * File can be processed if it exists
     * @return void
     */
    public function testCanProcessExistingFile(): void
    {
        $fileReader = new CSVFileReader(self::EXISTING_FILENAME);
        $this->assertIsIterable($fileReader->read());
    }
}
