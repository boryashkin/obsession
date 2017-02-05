<?php

namespace app\modules\time\models;

use Yii;
use yii\base\InvalidCallException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Transaction;
use yii\validators\DateValidator;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $start
 * @property integer $state
 * @property integer $duration
 * @property integer $planId
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Plan[] $plans
 */
class Task extends ActiveRecord
{
    /** Possible states for task */
    const STATES = [
        0 => 'New',
        1 => 'Moved once',//last chance to move start-date
        2 => 'Moved twice',
        4 => 'Done',//Enough moving
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['start', 'duration'], 'required'],
            [['start'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['state', 'created_at', 'updated_at', 'planId', 'duration'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['planId'], 'exist', 'targetClass' => Plan::className(), 'targetAttribute' => ['planId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'start' => 'Start',
            'state' => 'State',
            'duration' => 'Duration',
            'planId' => 'Plan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(Plan::className(), ['id' => 'planId']);
    }
}
