<?php

namespace app\modules\wallet\models;

use yii\base\Model;
use yii\db\ActiveQuery;

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
     * Same actions for each method
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    private function applyFilters(ActiveQuery $query)
    {
        if ($this->validate()) {
            if ($this->dateFrom && !$this->hasErrors('dateFrom')) {
                $dateFrom = new \DateTime($this->dateFrom);
                $query->andWhere(['>', 'created_at', $dateFrom->getTimestamp()]);
            }
            if ($this->dateTo && !$this->hasErrors('dateTo')) {
                $dateTo = new \DateTime($this->dateTo);
                $query->andWhere(['<', 'created_at', $dateTo->getTimestamp()]);
            }
        }

        return $query;
    }
}
