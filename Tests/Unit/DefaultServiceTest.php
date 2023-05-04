<?php

namespace Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Services\AvailableServices;
use Services\DefaultService;
use Services\ServiceProducer;

class DefaultServiceTest extends TestCase
{
    /** @var string RATES_FILENAME */
    private const RATES_FILENAME = 'Tests/Mock/Data/rates.json';

    /**
     * Retrieve rates
     * @return void
     */
    public function testCallRatesApiAndReturnsValidResponse(): void
    {
        // Create Service
        $serviceProducer = $this->mockServiceProducer();
        $service = $serviceProducer->create(AvailableServices::RATES_URL, true);

        // Assert
        $this->assertIsArray($service->getData());
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /**
     * Mock service producer
     */
    private function mockServiceProducer()
    {
        // Get service mock
        $defaultServiceMock = $this->mockService();

        // Create service producer mock
        $serviceProducerMock = \Mockery::mock(ServiceProducer::class);
        $serviceProducerMock->shouldReceive('create')->andReturns($defaultServiceMock);

        // Return
        return $serviceProducerMock;
    }

    /**
     * Mock service
     * @return MockObject
     */
    private function mockService(): MockObject
    {
        // Default service mock
        $defaultServiceMock = $this->getMockBuilder(DefaultService::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData'])
            ->getMock();

        // Mock getData method
        $defaultServiceMock->expects($this->any())
            ->method('getData')
            ->willReturnCallback(static function () {
                return json_decode(
                    file_get_contents(self::RATES_FILENAME),
                    true
                );
            });

        // Return
        return $defaultServiceMock;
    }
}
