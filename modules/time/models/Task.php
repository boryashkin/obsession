<?php

namespace app\modules\time\models;

use Yii;
use yii\base\InvalidCallException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Transaction;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $start
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PlanTask[] $planTasks
 * @property Plan[] $plans
 */
class Task extends ActiveRecord
{
    /** Possible states for task */
    const STATES = [
        0 => 'New',
        1 => 'Moved once',
        2 => 'Moved twice',//last chance to move start-date
        3 => 'Enough',
    ];

    /** @var integer This task is part of the plan */
    public $planId;

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
            [['start'], 'required'],
            [['start'], 'safe'],
            [['state', 'created_at', 'updated_at', 'planId'], 'integer'],
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

    /** @var Transaction */
    private $transaction;

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        $this->transaction = $this->getDb()->beginTransaction();
        return parent::beforeSave($insert);
    }

    /** @inheritdoc */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->planId) {
            try {
                $this->link('plan', Plan::findOne($this->planId));
            } catch (InvalidCallException $e) {
                $this->addError('planId', 'Failed to link task to plan');
                $this->transaction->rollBack();

                return false;
            }
        }
        $this->transaction->commit();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanTask()
    {
        return $this->hasOne(PlanTask::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(Plan::className(), ['id' => 'plan_id'])->via('planTask');
    }
}
