<?php

namespace app\modules\budget\models;

use yii\base\Model;
use yii\db\Expression;

/**
 * Class BudgetStatistic
 * @package app\modules\budget\models
 */
class BudgetStatistic extends Model
{
    /**
     * @return array
     * [
     *  [expectedSum] => 1
     *  [realSum] =>
     *  [expectedIncome] => 1
     *  [expectedOutgo] =>
     *  [realIncome] =>
     *  [realOutgo] =>
     *  [totalRecords] => 0
     *  [totalUndone] => 0
     * ]
     */
    public static function getSums()
    {
        return Budget::find()->select([
            new Expression('sum(expectedSum) as expectedSum'),
            new Expression('sum(realSum) as realSum'),
            new Expression(
                '('
                . Budget::find()->where(['>', 'expectedSum', 0])
                    ->select(new Expression('sum(expectedSum)'))
                    ->createCommand()->rawSql
                . ') as expectedIncome'
            ),
            new Expression(
                '('
                . Budget::find()->where(['<', 'expectedSum', 0])
                    ->select(new Expression('sum(expectedSum)'))
                    ->createCommand()->rawSql
                . ') as expectedOutgo'
            ),
            new Expression(
                '('
                . Budget::find()->where(['>', 'realSum', 0])
                    ->select(new Expression('sum(realSum)'))
                    ->createCommand()->rawSql
                . ') as realIncome'
            ),
            new Expression(
                '('
                . Budget::find()->where(['<', 'realSum', 0])
                    ->select(new Expression('sum(realSum)'))
                    ->createCommand()->rawSql
                . ') as realOutgo'
            ),
            new Expression('count(done) as totalRecords'),
            new Expression(
                '('
                . Budget::find()->where(['done' => false])
                    ->select(new Expression('count(*) as done'))
                    ->createCommand()->rawSql
                . ') as totalUndone'
            )
        ])->asArray()->one();
    }
}