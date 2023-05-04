<?php

namespace Operations;

use DateTime;
use Entities\FinancialOperationInfo;
use Entities\User;
use Entities\Week;
use Exception;

class PrivateClientWithdrawFinancialOperation extends DefaultFinancialOperation implements FinancialOperation
{
    /**
     * Should do preliminary work?
     * @return bool
     */
    public function shouldExecute(): bool
    {
        return true;
    }

    /**
     * Execute main behavior
     * @param FinancialOperationInfo $withdrawOperationInfo
     * @return FinancialOperationInfo
     * @throws Exception
     */
    public function execute(FinancialOperationInfo $withdrawOperationInfo): FinancialOperationInfo
    {
        // Set values
        $date = $withdrawOperationInfo->date;
        $week = $withdrawOperationInfo->week;
        $user = $withdrawOperationInfo->user;
        $amount = $withdrawOperationInfo->amount;

        // Get week object based on current transaction
        $weekForCurrentTransaction = $this->getWeekForCurrentTransaction($date, $week);

        // If the week is different from the previous transaction week then reset values
        if ($weekForCurrentTransaction->changed) {
            $week = $weekForCurrentTransaction;
            $week->changed = false;
            $user->resetValues();
        }

        // Total value of withdrawn amount this week increment
        $user->totalWithdrawnAmountThisWeek += $withdrawOperationInfo->amount;

        // Total withdraws this week increment
        $user->totalWithdrawsThisWeek++;

        // Amount
        $amountToBeTaxed = $this->getAmountToBeTaxed($user, $amount);

        // Return new financial operation info
        return new FinancialOperationInfo($date, $user, $week, $amountToBeTaxed);
    }

    /**
     * Get week for current financial operation
     * @param DateTime $date
     * @param Week $week
     * @return Week
     * @throws Exception
     */
    private function getWeekForCurrentTransaction(DateTime $date, Week $week): Week
    {
        // Set values
        $currentTransactionFromDate = Week::getFrom($date);
        $previousTransactionFromDate = null;

        // If from date is set then get the previous transaction date
        if (null !== $week->from) {
            $previousTransactionFromDate = Week::getFrom($week->from);
        }

        $newWeek = $week;

        // If there is a difference we should return a new object with rew Data
        if ($previousTransactionFromDate != $currentTransactionFromDate) {
            $to = Week::getTo($date);
            $newWeek->changed = null !== $week->from;
            $newWeek->from = $currentTransactionFromDate;
            $newWeek->to = $to;
        }

        // Return result
        return $newWeek;
    }

    /**
     * Get amount to be taxed
     * @param User $user
     * @param float $amount
     * @return float
     */
    private function getAmountToBeTaxed(User $user, float $amount): float
    {
        if (!$user->doesUserQualifyForFreeTransaction()) {
            return $amount;
        }

        $amountToBeTaxed = 0;

        // Perform per-user calculations
        $moneyLeftAfterTransactionAmountBeingPayed = $user->totalMoneyLeftThisWeek - $amount;
        $user->totalMoneyLeftThisWeek = $moneyLeftAfterTransactionAmountBeingPayed;

        // If the amount is more than the allowed one we calculate the fee on the money left
        if ($moneyLeftAfterTransactionAmountBeingPayed < 0) {
            $amountToBeTaxed = abs($moneyLeftAfterTransactionAmountBeingPayed);
            $user->totalWithdrawnAmountThisWeek = 0;
        }

        // Return result
        return $amountToBeTaxed;
    }

}
