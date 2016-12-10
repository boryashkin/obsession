<?php

namespace app\modules\wallet\models;

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

    public function withOperationId()
    {
        return $this->joinWith('operation')->select([
            '{{credit}}.*',
            '{{operation}}.id as operationId',
        ])->asArray();
    }
}
