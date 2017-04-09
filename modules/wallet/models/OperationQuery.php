<?php

namespace app\modules\wallet\models;
use yii\db\ActiveQuery;
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

    /**
     * @return float
     */
    public function getBalance()
    {
        return (float)$this->queryScalar('sum(sum)', null);
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
     * @return ActiveQuery
     */
    public function getTagTotals()
    {
        return $this->joinWith('tags')->select([
            '{{tag}}.id',
            '{{tag}}.name',
            new Expression('SUM({{operation}}.sum) as sum'),
        ])
            ->andWhere('{{operation}}.sum < 0 AND {{operation.creditId}} IS NOT NULL')
            ->orWhere('{{operation}}.creditId IS NULL')
            ->groupBy(['{{tag}}.id', '{{tag}}.name'])->asArray();
    }

    /**
     * @return ActiveQuery
     */
    public function getDailyStat()
    {
        return $this->select([
            new Expression('sum(sum) as total'),
            new Expression('FROM_UNIXTIME(created_at, \'%Y-%m-%d\') as date'),
            new Expression('created_at as id'),
        ])
            ->where('creditId is NULL')
            ->andWhere('sum < 0')
            ->groupBy(['date', 'id'])->asArray();
    }

    /**
     * @return ActiveQuery
     */
    public function getSumExpenses()
    {
        return $this->select([
            new Expression('sum(sum) as total'),

        ])
            ->where('sum < 0')
            ->asArray();
    }

    /**
     * Get array of sums grouped by searched names
     * @param array $names [
     *  'column' => 'me',// will be (SELECT count(sum) FROM o WHERE desc LIKE "%me%") as column
     * ]
     * @return array
     */
    public function getStatByNames(array $names)
    {
        if (!$names) {
            return [];
        }
        // build a sql
        $sql = 'SELECT ';
        $total = count($names);
        for ($i = 1; $i <= $total; $i++) {
            $sql .= "(SELECT count(sum) FROM operation WHERE description LIKE :val{$i}) as :col{$i} ";
            if ($i != $total) {
                $sql .= ', ';
            }
        }
        $sql .= 'FROM dual';

        $bindParams = [];
        $i = 1;
        foreach ($names as $column => $name) {
            $bindParams[":col{$i}"] = $column;
            $bindParams[":val{$i}"] = $name . '%';
            $i++;
        }

        return \Yii::$app->db->createCommand($sql)->bindValues($bindParams)->queryOne();
    }
}
