<?php

namespace app\modules\lrm\models;

use Yii;

/**
 * This is the model class for table "{{%contact}}".
 *
 * @property integer $id
 * @property integer $personId
 * @property string $contact
 * @property string $type
 * @property string $note
 * @property integer $sort
 *
 * @property Person $person
 */
class Contact extends \yii\db\ActiveRecord
{
    public static $types = [
        0 => 'phone',
        1 => 'instagram',
        2 => 'vk',
        3 => 'mail',
    ];

    public static $typeLink = [
        0 => 'tel:',
        1 => 'http://instagram.com/',
        2 => 'http://vk.com/',
        3 => 'mailto:',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['personId', 'sort'], 'integer'],
            [['contact', 'type', 'note'], 'string', 'max' => 255],
            [['personId'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['personId' => 'id']],
            [['sort'], 'default', 'value' => 10],
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
            'contact' => 'Contact',
            'type' => 'Type',
            'note' => 'Note',
            'sort' => 'Sort',
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
