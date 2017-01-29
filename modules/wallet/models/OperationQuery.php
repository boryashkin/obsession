<?php

namespace app\modules\wallet\models;
use yii\db\Expression;
use yii\db\Query;

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
        ])
            ->where('{{operation}}.sum < 0 AND {{operation.creditId}} IS NOT NULL')
            ->orWhere('{{operation}}.creditId IS NULL')
            ->groupBy('{{tag}}.id')->asArray()->all();
    }

    public function getBorisdStat()
    {
        $sql = <<<SQL
SELECT
(SELECT count(sum) FROM operation WHERE description LIKE "%чай%") as tea,
(SELECT count(sum) FROM operation WHERE description LIKE "%хлеб%") as bread,
(SELECT count(sum) FROM operation WHERE description LIKE "%кетчуп%") as ketchup,
(SELECT count(sum) FROM operation WHERE description LIKE "%яйц%") as egg
FROM dual
SQL;

        return \Yii::$app->db->createCommand($sql)->queryOne();
    }
}
