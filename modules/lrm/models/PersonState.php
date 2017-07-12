<?php

namespace app\modules\lrm\models;

use Yii;

/**
 * //todo: order property for sorting
 * This is the model class for table "{{%personState}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Person[] $people
 */
class PersonState extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%personState}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['stateId' => 'id']);
    }
}
