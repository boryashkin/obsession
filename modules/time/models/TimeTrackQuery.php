<?php

namespace app\modules\time\models;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[TimeTrack]].
 *
 * @see Activity
 */
class TimeTrackQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['stop' => null]);
    }

    /**
     * @inheritdoc
     * @return Activity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Activity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return array
     */
    public function getStat()
    {
        return $this->select([
            'timeTrack.id as id',
            'name',
            'activityId',
            new Expression('DATE(start) as date'),
            new Expression('sum(TIMESTAMPDIFF(SECOND, start, stop)) sum'),
        ])
            ->leftJoin('activity', 'timeTrack.activityId = activity.id')
            ->where(['NOT', ['stop' => null]])
            ->groupBy([
                'id',
                'name',
                'date',
                'activityId',
            ])
            ->indexBy('id')
            ->asArray()->all();
    }

    /** @return ActiveQuery */
    public function getSumSeconds()
    {
        return $this->select([
            new Expression('sum(TIMESTAMPDIFF(SECOND, start, stop)) sum'),
        ]);
    }
}
