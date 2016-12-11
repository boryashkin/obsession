<?php

namespace app\modules\time\models;
use yii\db\ActiveQuery;

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
}
