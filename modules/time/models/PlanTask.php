<?php

namespace app\modules\time\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%plan_task}}".
 *
 * @property integer $plan_id
 * @property integer $task_id
 *
 * @property Task $task
 * @property Plan $plan
 */
class PlanTask extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plan_task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plan_id', 'task_id'], 'required'],
            [['plan_id', 'task_id'], 'integer'],
            [['task_id'], 'exist', 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['plan_id'], 'exist', 'targetClass' => Plan::className(), 'targetAttribute' => ['plan_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'plan_id' => 'Plan ID',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(Plan::className(), ['id' => 'plan_id']);
    }
}
