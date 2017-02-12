<?php

namespace app\modules\budget\models;

use app\modules\budget\models\BudgetQuery;
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
    /** @var bool Create same record for each month of year */
    public $repeatForYear;

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
            ['description', 'string'],
            ['repeatForYear', 'boolean'],
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

    /** @inheritdoc */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $date = new \DateTime($this->expectedDate);
            if ($this->repeatForYear) {
                while ($date->format('m') < 12) {
                    $currentMonth = $date->format('m');
                    $date->modify('next month');
                    // check if next month doesn't have this day (like 31 february)
                    if (($date->format('m') - $currentMonth) > 1) {
                        // but we need the next month
                        $date = new \DateTime($this->expectedDate);
                        $date->modify('last day of next month');
                    }

                    $duplicate = new Budget();
                    $duplicate->attributes = $this->attributes;
                    $duplicate->expectedDate = $date->format('Y-m-d');
                    $duplicate->repeatForYear = false;
                    $duplicate->save();
                }
            }
        }
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
            'repeatForYear' => 'Repeat it for each next month of this year',
        ];
    }

    /**
     * @inheritdoc
     * @return BudgetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BudgetQuery(get_called_class());
    }
}
