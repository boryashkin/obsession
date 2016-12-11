<?php

namespace app\modules\wallet\models;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[Credit]].
 *
 * @see Credit
 */
class CreditQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Credit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Credit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Join the sum and id of operations
     *
     * @return $this
     */
    public function withOperationId()
    {
        return $this->joinWith('operation')->select([
            '{{credit}}.*',
            '{{operation}}.id as operationId, {{operation}}.sum',
        ])->asArray();
    }

    /**
     * The amount of debt that must be returned
     *
     * @return float
     */
    public function sumOfCredits()
    {
        return (float)$this->joinWith('operation')
            ->where(['returned' => false])->queryScalar(new Expression('sum({{operation}}.sum)'), null);
    }
}
