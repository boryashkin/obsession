<?php

namespace app\modules\lrm\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%interactionNote}}".
 *
 * @property integer $id
 * @property integer $personId
 * @property string $text
 * @property integer $appraisal
 * @property string $date
 * @property integer $createdAt
 * @property integer $updatedAt
 *
 * @property Person $person
 */
class InteractionNote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%interactionNote}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personId', 'text', 'appraisal'], 'required'],
            [['personId', 'appraisal'], 'integer'],
            [['text'], 'string'],
            [['date'], 'safe'],
            [['personId'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['personId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'personId' => 'Person ID',
            'text' => 'Text',
            'appraisal' => 'Appraisal',
            'date' => 'Date',
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
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'personId']);
    }
}
