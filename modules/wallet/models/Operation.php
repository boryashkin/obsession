<?php

namespace app\modules\wallet\models;

use app\models\Tag;
use Yii;
use yii\base\InvalidValueException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%operation}}".
 *
 * @property integer $id
 * @property string $sum
 * @property string $description
 * @property integer $isSalary
 * @property integer $created_at
 * @property integer $updated_at
 * @property boolean $isCredit
 * @property integer $creditId
 *
 * @property array $tagsArray
 * @property integer $toCreditId
 *
 * @property Credit $credit
 */
class Operation extends ActiveRecord
{
    /**
     * @var boolean
     */
    protected $_isCredit;
    /**
     * Link this operation to the existing credit
     *
     * @var integer
     */
    protected $_toCreditId;

    public function getToCreditId()
    {
        if (is_null($this->_toCreditId)) {
            if ($this->isCredit) {
                $this->_toCreditId = $this->creditId;
            }
        }

        return $this->_toCreditId;
    }
    public function setToCreditId($creditId)
    {
        $this->_toCreditId = (int)$creditId;
    }

    /**
     * @var array
     */
    protected $_tagsArray = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation}}';
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
            [['sum'], 'required'],
            [['isSalary', 'isCredit'], 'boolean'],
            [['creditId', 'toCreditId'], 'integer'],
            [['sum'], 'number'],
            ['tagsArray', 'safe'],
            [['created_at', 'updated_at'], 'integer'],
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
            'isSalary' => 'Is salary',
            'creditId' => 'Credit',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'tagsArray' => 'Tags',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('operation_tag', ['operation_id' => 'id']);
    }

    public function getTagsArray()
    {
        if (!$this->_tagsArray) {
            $this->_tagsArray = $this->getTags()->select('id')->column();
        }

        return $this->_tagsArray;
    }

    private function updateTags()
    {
        $currentIds = $this->getTags()->select('id')->column();
        $newIds = $this->tagsArray;

        foreach (array_filter(array_diff($newIds, $currentIds)) as $id) {
            if ($item = Tag::findOne($id)) {
                $this->link('tags', $item);
            }
        }
        foreach (array_filter(array_diff($currentIds, $newIds)) as $id) {
            if ($item = Tag::findOne($id)) {
                $this->unlink('tags', $item, true);
            }
        }
    }

    public function setTagsArray($tags)
    {
        if ($tags && !is_array($tags)) {
            throw new InvalidValueException('is should be an array');
        } elseif (!$tags) {
            $tags = [];
        }

        $this->_tagsArray = $tags;
    }

    public function getIsCredit()
    {
        if ($this->_isCredit === null) {
            if (!$this->creditId && $this->credit) {
                $this->creditId = $this->credit->id;
            }
            $this->_isCredit = !empty($this->creditId);
        }

        return $this->_isCredit;
    }

    public function setIsCredit($isCredit)
    {
        $this->_isCredit = (bool)$isCredit;
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

    public function beforeSave($insert)
    {
        if ($this->isCredit && ($this->credit->attributes != $this->credit->oldAttributes)) {
            $this->credit->returned = false;
            //@todo: transaction
            if ($this->credit->save()) {
                $this->creditId = $this->credit->id;
            } else {
                return false;
            }
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->updateTags();
    }
}
