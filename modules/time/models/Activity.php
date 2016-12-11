<?php

namespace app\modules\time\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\validators\EachValidator;

/**
 * This is the model class for table "activity".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property TimeTrack[] $timeTracks
 * @property TimeTrack $activeTrack
 * @property integer $totalTrackedSeconds
 */
class Activity extends ActiveRecord
{
    /**
     * Started but not finished yet
     *
     * @var TimeTrack|null
     */
    protected $_activeTrack;
    /**
     * Amount of seconds from finished tracks
     *
     * @var integer
     */
    protected $_totalTrackedSeconds;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['name'], 'unique'],
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

    /**
     * @return int
     */
    public function getTotalTrackedSeconds()
    {
        if ($this->_totalTrackedSeconds === null) {
            $this->_totalTrackedSeconds = (int)$this->getTimeTracks()->andWhere(['not', ['stop' => null]])
                ->select(new Expression('TIMESTAMPDIFF(SECOND, start, stop)'))->scalar();
        }

        return $this->_totalTrackedSeconds;
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function getActiveTrack()
    {
        if ($this->_activeTrack === null) {
            $this->_activeTrack = $this->getTimeTracks()->andWhere(['stop' => null])->one();
        }

        return $this->_activeTrack;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTracks()
    {
        return $this->hasMany(TimeTrack::className(), ['activityId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ActivityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActivityQuery(get_called_class());
    }
}
