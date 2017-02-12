<?php

namespace app\modules\budget\models;

use app\modules\budget\models\Budget;
use yii\db\ActiveQuery;
use yii\db\Expression;
use DateTime;

/**
 * Class BudgetQuery
 * @package app\modules\wallet\models
 */
class BudgetQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function selectExpectedSum()
    {
        return $this->select([
            new Expression('SUM(expectedSum) as sum'),
        ]);
    }

    /**
     * @return $this
     */
    public function selectExpectedExpensesSum()
    {
        return $this->selectExpectedSum()
            ->where(['<', 'expectedSum', 0]);
    }

    /**
     * @return $this
     */
    public function selectExpectedIncomeSum()
    {
        return $this->selectExpectedSum()
            ->where(['>', 'expectedSum', 0]);
    }

    /**
     * @return $this
     */
    public function whereNotDone()
    {
        return $this->andWhere(['done' => false]);
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function whereExpectedDateFrom(\DateTime $date)
    {
        return $this->andWhere(['>=', 'expectedDate', $date->format('Y-m-d')]);
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function whereExpectedDateTo(\DateTime $date)
    {
        return $this->andWhere(['<=', 'expectedDate', $date->format('Y-m-d')]);
    }

    /**
     * @inheritdoc
     * @return Budget[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Budget|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
