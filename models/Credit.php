<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%credit}}".
 *
 * @property integer $id
 * @property integer $returned
 * @property string $dueDate
 * @property string $creditor
 * @property integer $updated_at
 *
 * @property Operation $operation
 */
class Credit extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%credit}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
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
            [['creditor'], 'required'],
            [['returned', 'updated_at'], 'integer'],
            [['dueDate'], 'date', 'format' => 'd.m.Y'],
            [['creditor'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'returned' => 'Returned',
            'dueDate' => 'Due Date',
            'creditor' => 'Creditor',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperation()
    {
        return $this->hasOne(Operation::className(), ['creditId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return CreditQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CreditQuery(get_called_class());
    }
}
