<?php

namespace app\modules\wallet\helpers;

use app\helpers\DateHelper;
use app\modules\budget\models\Budget;
use app\modules\wallet\models\Operation;
use DateTime;

/**
 * Wallet helper
 * @package app\modules\wallet\models
 */
class Wallet
{
    /**
     * @return float
     */
    public static function getBalance()
    {
        return Operation::find()->getBalance();
    }

    /**
     * For this month
     * @return $this
     */
    public static function isBalanceEnoughToCoverBudget()
    {
        $expenses = self::getExpectedExpensesSumForMonth(new DateTime());

        if (self::getBalance() >= -$expenses) {
            return true;
        }

        return false;
    }

    /**
     * @param DateTime $date
     * @return float
     */
    public static function getExpectedExpensesSumForMonth(DateTime $date)
    {
        return (float)Budget::find()->selectExpectedExpensesSum()
            ->whereNotDone()
            ->whereExpectedDateFrom(DateHelper::getStartOfMonth($date))
            ->whereExpectedDateTo(DateHelper::getEndOfMonth($date))
            ->scalar();
    }

    /**
     * @param DateTime $date
     * @return float
     */
    public static function getExpectedIncomeSumForMonth(DateTime $date)
    {
        return (float)Budget::find()->selectExpectedIncomeSum()
            ->whereNotDone()
            ->whereExpectedDateFrom(DateHelper::getStartOfMonth($date))
            ->whereExpectedDateTo(DateHelper::getEndOfMonth($date))
            ->scalar();
    }
}
