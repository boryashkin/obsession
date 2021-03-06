<?php

namespace app\modules\wallet\models;

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
 * @property Operation[] $operations
 */
class Credit extends ActiveRecord
{
    /**
     * Критическое количество дней до выплаты долга
     */
    const WARNING_NUM_DAYS = 3;

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
            [['dueDate'], 'date', 'format' => 'php:Y-m-d'],
            [
                'dueDate', 'filter', 'filter' => function ($value) {
                    return date('Y-m-d H:i:s', strtotime($value));
                },
            ],
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
    public function getOperations()
    {
        return $this->hasMany(Operation::className(), ['creditId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return CreditQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CreditQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $credit = self::findOne(['creditor' => $this->creditor]);
            if ($credit) {
                $this->addError('creditor', 'Creditor already exists');

                return false;
            }
        }

        return parent::beforeSave($insert);
    }
}
