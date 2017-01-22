<?php

namespace app\modules\reading\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%reading}}".
 *
 * @property integer $id
 * @property string $category
 * @property string $name
 * @property string $link
 * @property integer $done
 * @property integer $created_at
 * @property integer $updated_at
 */
class Reading extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reading}}';
    }

    /** @inheritdoc */
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
            [['category'], 'required'],
            [['link'], 'string'],
            [['done', 'created_at', 'updated_at'], 'integer'],
            [['category', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'name' => 'Name',
            'link' => 'Link',
            'done' => 'Done',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
