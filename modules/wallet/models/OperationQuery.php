<?php

namespace app\modules\wallet\models;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[Operation]].
 *
 * @see Operation
 */
class OperationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Operation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Operation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getBalance()
    {
        return $this->queryScalar('sum(sum)', null);
    }

    public function withCredits()
    {
        return $this->joinWith('credit')->select([
            '{{operation}}.*',
            '{{credit}}.returned'
        ])->asArray();
    }

    /**
     * Sum by tags
     *
     * @return Operation[]|array
     */
    public function getTagTotals()
    {
        return $this->joinWith('tags')->select([
            '{{tag}}.id',
            '{{tag}}.name',
            new Expression('SUM({{operation}}.sum) as sum'),
        ])->groupBy('{{tag}}.id')->asArray()->all();
    }
}
