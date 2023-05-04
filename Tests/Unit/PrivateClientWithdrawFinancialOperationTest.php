<?php

namespace Tests\Unit;

use Base\Fees;
use Base\WithdrawsDefinitions;
use Entities\User;
use Entities\UserInfo;
use Entities\Week;
use Operations\FinancialOperationInfoProducer;
use Operations\FinancialOperationProducer;
use Parsers\OperationInfoParser;
use Parsers\Parser;
use PHPUnit\Framework\TestCase;
use Readers\CSVFileReader;
use Readers\FileReader;

class PrivateClientWithdrawFinancialOperationTest extends TestCase
{
    /** @var int FREE_OF_CHARGE_AMOUNT */
    private const FREE_OF_CHARGE_AMOUNT = 0;

    /** @var array $usersMap */
    private array $usersMap = [];

    /** @var FileReader $fileReader */
    private FileReader $fileReader;

    /** @var Parser $parser */
    private Parser $parser;

    protected function setUp(): void
    {
        $this->parser = new OperationInfoParser();
    }

    /**
     * I expect to be not charged a fee if transactions are below 3 and total amount is below the free of charge amount
     * @return void
     */
    public function testNoCommissionForAmountUnderTheFreeOfChargeAmountForWeekWithThreeTransaction(): void
    {
        // Set file reader
        $this->fileReader = new CSVFileReader('Tests/Mock/Data/free_of_charge_transactions.csv');

        // Loop through operations
        $this->process(self::FREE_OF_CHARGE_AMOUNT);
    }

    /**
     * I expect to be not charged a fee if transactions are above 3 and total amount is below or equal to the free of charge amount
     * @return void
     */
    public function testCommissionNotFreeForAmountUnderTheFreeOfChargeAmountForWeekWithFourTransaction(): void
    {
        // Set file reader
        $this->fileReader = new CSVFileReader('Tests/Mock/Data/exceeded_weekly_free_of_charge_withdraws_count.csv');

        // Loop through operations
        $commission = 100 * (Fees::WITHDRAW_PRIVATE_CLIENT_FEE / 100);
        $this->process($commission);
    }

    /**
     * I expect to be charged a fee if transaction amount is above the free of charge amount
     * @return void
     */
    public function testCommissionNotFreeForAmountAboveTheFreeOfChargeAmountForWeekWithOneTransaction(): void
    {
        // Set file reader
        $this->fileReader = new CSVFileReader('Tests/Mock/Data/exceeded_amount_initially.csv');

        // Loop through operations
        $commission = (1200 - WithdrawsDefinitions::MAX_WITHDRAW_AMOUNT_WITH_NO_FEES) * (Fees::WITHDRAW_PRIVATE_CLIENT_FEE / 100);
        $this->process($commission);
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
     * Process transactions
     * @param float $assert
     * @return void
     */
    private function process(float $assert): void
    {
        $total = 0;
        foreach ($this->fileReader->read() as $operation) {
            // Parse
            $parsedOperation = $this->parser->parse($operation);

            // Extract variables
            $user = $parsedOperation->user;
            $operationInfo = $parsedOperation->operationInfo;

            // Convert the value to euro
            $amountToBeTaxed = $operationInfo->amount;

            // Add user info to map
            $this->addUserInfoToMap($user);

            // Retrieve user info
            $userInfo = $this->retrieveUserInfoFromMap($user);

            // Get financial operation handler
            $financialOperationHandler = FinancialOperationProducer::create(
                $operationInfo->operationType,
                $user->type,
            );

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

            // Calculate fee
            $fee = $financialOperationHandler->calculateCommission($amountToBeTaxed);

            // Total
            $total += $fee;
        }

        // Assert
        $this->assertTrue($assert == $total);
    }

}
