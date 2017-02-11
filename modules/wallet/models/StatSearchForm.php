<?php

namespace app\modules\wallet\models;

use yii\base\Model;

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
    public function searchQuery()
    {
        $query = Operation::find()->getTagTotals();

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
