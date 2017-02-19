<?php

namespace app\modules\wallet\models;

use app\modules\budget\models\Budget;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * Class StatSearchForm
 * @package app\modules\wallet\models
 */
class StatSearchForm extends Model
{
    /** @var string */
    public $dateFrom;
    /** @var string */
    public $dateTo;

    /** @inheritdoc */
    public function rules()
    {
        return [
            [['dateFrom', 'dateTo'], 'date', 'format' => 'php:Y-m-d'],
            [['dateFrom', 'dateTo'], 'default', 'value' => null],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function searchTagTotalsQuery()
    {
        $query = Operation::find()->getTagTotals();

        return $this->applyFilters($query);
    }
    
    public function searchTotalExpensesQuery()
    {
        $query = Operation::find()->getSumExpenses();

        return $this->applyFilters($query);
    }

    /**
     * @return ActiveQuery
     */
    public function searchExpensesByBudget()
    {
        $query = Budget::find()->joinWith('operations o', false)->select([
            'budget.id',
            'budget.name',
            new Expression('budget.expectedSum - sum(o.sum) as sum')
        ])->groupBy(['budget.id', 'budget.name'])
        ->andWhere(['<', 'budget.expectedSum', 0]);

        return $this->applyFilters($query, 'o');
    }

    /**
     * Same actions for each method
     * @param ActiveQuery $query
     * @param string $alias
     * @return ActiveQuery
     */
    private function applyFilters(ActiveQuery $query, $alias = null)
    {
        if (!$alias || !is_string($alias)) {
            $alias = '';
        } else {
            $alias .= '.';
        }
        if ($this->validate()) {
            if ($this->dateFrom && !$this->hasErrors('dateFrom')) {
                $dateFrom = new \DateTime($this->dateFrom);
                $query->andWhere(['>', $alias . 'created_at', $dateFrom->getTimestamp()]);
            }
            if ($this->dateTo && !$this->hasErrors('dateTo')) {
                $dateTo = new \DateTime($this->dateTo);
                $query->andWhere(['<', $alias . 'created_at', $dateTo->getTimestamp()]);
            }
        }

        return $query;
    }
}
