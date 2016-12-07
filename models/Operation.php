<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%operation}}".
 *
 * @property integer $id
 * @property string $sum
 * @property string $description
 * @property integer $salary
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Credit $credit
 */
class Operation extends ActiveRecord
{
    public $isCredit = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation}}';
    }

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
            [['sum'], 'required'],
            ['isCredit', 'boolean'],
            ['creditId', 'integer'],
            [['sum'], 'number'],
            [['salary', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sum' => 'Sum',
            'description' => 'Description',
            'salary' => 'Salary',
            'creditId' => 'Credit',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return Credit
     */
    public function getCredit()
    {
        return $this->hasOne(Credit::className(), ['id' => 'creditId']);
    }

    public function setCredit(Credit $credit)
    {
        if ($this->credit) {
            $this->credit->attributes = $credit->attributes;

        } else {
            $this->credit = $credit;
        }
    }

    /**
     * @inheritdoc
     * @return OperationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OperationQuery(get_called_class());
    }

    public function getBalance()
    {
        return $this->find()->getBalance();
    }

    public function beforeSave($insert)
    {
        if($insert && $this->credit) {
            //@todo: transaction
            if ($this->credit->save()) {
                $this->creditId = $this->credit->id;
            } else {
                return false;
            }
            
        }

        return parent::beforeSave($insert);
    }
}
