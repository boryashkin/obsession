<?php

namespace app\modules\lrm\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%person}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $fullName
 * @property string $birthdate
 * @property string $description
 * @property string $gender
 * @property int $stateId
 * @property integer $createdAt
 * @property integer $updatedAt
 *
 * @property InteractionNote[] $interactionNotes
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%person}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthdate'], 'date', 'format' => 'php:Y-m-d'],
            [['name', 'fullName', 'description'], 'string', 'max' => 255],
            [['gender'], 'in', 'range' => ['m', 'f']],
            [['stateId'], 'exist', 'targetClass' => PersonState::class, 'targetAttribute' => 'id'],
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
            'fullName' => 'Full Name',
            'birthdate' => 'Birthdate',
            'description' => 'Description',
            'gender' => 'Gender',
            'stateId' => 'State',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createdAt', 'updatedAt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updatedAt'],
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInteractionNotes()
    {
        return $this->hasMany(InteractionNote::className(), ['personId' => 'id']);
    }

    public function getState()
    {
        return $this->hasOne(PersonState::class, ['id' => 'stateId']);
    }
}
