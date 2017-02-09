<?php

namespace app\modules\time\models;

use app\modules\reading\models\Reading;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%plan}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $completeness
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Reading[] $readings
 * @property Task[] $tasks
 * @property TimeTrack[] $timeTracks
 */
class Plan extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['completeness'], 'integer', 'min' => 0, 'max' => 100],
            [['name'], 'string', 'max' => 255],
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
            'completeness' => 'Completeness',
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
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['planId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReadings()
    {
        return $this->hasMany(Reading::className(), ['planId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTracks()
    {
        return $this->hasMany(TimeTrack::className(), ['planId' => 'id']);
    }
}
