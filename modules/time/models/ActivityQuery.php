<?php

namespace app\modules\time\models;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Activity]].
 *
 * @see Activity
 */
class ActivityQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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

    public function withActiveTracks()
    {
        return $this->joinWith('activeTrack');
    }
}
