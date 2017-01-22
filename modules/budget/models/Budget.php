<?php

namespace app\modules\budget\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%budget}}".
 *
 * @property integer $id
 * @property string $firstExpectedDate
 * @property string $expectedDate
 * @property string $realDate
 * @property string $name
 * @property string $description
 * @property string $expectedSum
 * @property string $realSum
 * @property integer $done
 * @property integer $created_at
 * @property integer $updated_at
 */
class Budget extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%budget}}';
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expectedDate', 'expectedSum'], 'required'],
            [['firstExpectedDate', 'expectedDate', 'realDate'], 'date', 'format' => 'php:Y-m-d'],
            [['expectedSum', 'realSum'], 'number'],
            [['done', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['description', 'string']
        ];
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->firstExpectedDate = $this->expectedDate;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstExpectedDate' => 'First Expected Date',
            'expectedDate' => 'Expected Date',
            'realDate' => 'Real Date',
            'name' => 'Name',
            'description' => 'Description',
            'expectedSum' => 'Expected Sum',
            'realSum' => 'Real Sum',
            'done' => 'Done',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
