<?php

namespace Processors;

use Converters\DefaultConverter;
use Data\Mock\RatesMock;
use Entities\User;
use Entities\UserInfo;
use Entities\Week;
use Formatters\FormatterProducer;
use Operations\FinancialOperationInfoProducer;
use Operations\FinancialOperationProducer;
use Parsers\Parser;
use Readers\FileReader;
use Services\AvailableServices;
use Services\ServiceProducer;

class OperationProcessor
{
    /** @var FileReader $fileReader */
    private FileReader $fileReader;

    /** @var Parser $parser */
    private Parser $parser;

    /** @var array $usersMap */
    private array $usersMap;

    /**
     * @param FileReader $fileReader
     * @param Parser $parser
     */
    public function __construct(FileReader $fileReader, Parser $parser)
    {
        $this->fileReader = $fileReader;
        $this->parser = $parser;
        $this->usersMap = [];

        // Process
        $this->process();
    }


    /**
     * Add user information to the map
     * @param User $user
     * @return void
     */
    private function addUserInfoToMap(User $user): void
    {
        if (!array_key_exists($user->id, $this->usersMap)) {
            $this->usersMap[$user->id] = new UserInfo($user, new Week());
        }
    }

    /**
     * Retrieve user information from map
     * @param User $user
     * @return UserInfo|null
     */
    private function retrieveUserInfoFromMap(User $user): ?UserInfo
    {
        return $this->usersMap[$user->id];
    }

    /**
     * Overwrite user information
     * @param UserInfo $userInfo
     * @return void
     */
    private function overwriteUserInfoFromMap(UserInfo $userInfo): void
    {
        $this->usersMap[$userInfo->user->id] = $userInfo;
    }

    /**
     * Loops through all financial Operations and calculates the commission for each one of them
     * @return void
     */
    private function process(): void
    {
        // Get rates
        $rates = $this->getRates();

        // Loop through Operations
        foreach ($this->fileReader->read() as $operation) {
            // Parse
            $parsedOperation = $this->parser->parse($operation);

            // Extract variables
            $user = $parsedOperation->user;
            $operationInfo = $parsedOperation->operationInfo;

            // Convert the value to euro
            $amountToBeTaxed = DefaultConverter::convertToEuro($operationInfo->currency, $rates, $operationInfo->amount);

            // Add user info to map
            $this->addUserInfoToMap($user);

            // Retrieve user info
            $userInfo = $this->retrieveUserInfoFromMap($user);

            // Get financial operation handler
            $financialOperationHandler = FinancialOperationProducer::create(
                $operationInfo->operationType,
                $user->type,
            );

            // If the financial operation handler should do some preliminary calculations prior to calculating fee
            if ($financialOperationHandler->shouldExecute()) {
                // Create financial operation info object
                $financialOperationInfo = FinancialOperationInfoProducer::create(
                    $operationInfo,
                    $userInfo,
                    $amountToBeTaxed
                );

                // This method returns financial operation info after preliminary work being executed
                $newFinancialOperationInfo = $financialOperationHandler->execute($financialOperationInfo);

                // Amount to be taxed
                $amountToBeTaxed = $newFinancialOperationInfo->amount;

                // Overwrite user info
                $this->overwriteUserInfoFromMap(
                    new UserInfo(
                        $newFinancialOperationInfo->user,
                        $newFinancialOperationInfo->week
                    )
                );
            }

            // Convert the amount from euro
            $amountToBeTaxed = DefaultConverter::convertFromEuro($operationInfo->currency, $rates, $amountToBeTaxed);

            // Calculate fee
            $fee = $financialOperationHandler->calculateCommission($amountToBeTaxed);

            // Format result
            $formatter = FormatterProducer::create($operationInfo->currency);

            // Log
            echo($formatter->format($fee) . PHP_EOL);
        }
    }

    /**
     * Get rates from external service
     * @param bool $useMockFallback - if set to true then return Mock Data
     * @return array
     */
    private function getRates($useMockFallback = true): array
    {
        // Get remote data
        $service = ServiceProducer::create(AvailableServices::RATES_URL, true);
        $data = $service->getData();

        $rates = [];

        // Extract rates
        if (array_key_exists('rates', $data)) {
            $rates = $data['rates'];
        }

        // If the rates are not received still then use mock (if is applicable)
        if (empty($rates) && $useMockFallback) {
            $rates = RatesMock::getRates();
        }

        // Return
        return $rates;
    }

}
