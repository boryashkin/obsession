<?php

namespace app\modules\time\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "timeTrack".
 *
 * @property integer $id
 * @property integer $activityId
 * @property string $start dateTime
 * @property string $stop dateTime
 * @property string $note
 * @property integer $planId
 *
 * @property Activity $activity
 * @property \DateInterval $duration
 * @property \DateTime $dateTimeStart
 * @property \DateTime $dateTimeStop
 * @property integer $timestampStart
 */
class TimeTrack extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * Difference between stop and start
     *
     * @var \DateInterval
     */
    protected $_duration;

    protected $_dateTimeStart;
    protected $_dateTimeStop;
    private $_timestampStart;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'timeTrack';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activityId'], 'required'],
            ['start', 'required', 'on' => self::SCENARIO_CREATE],
            [['activityId', 'planId'], 'integer'],
            [['start', 'stop'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            ['note', 'string'],
            ['stop', 'required', 'when' => function ($model) {
                /* @var TimeTrack $model */
                if ($this->scenario == self::SCENARIO_UPDATE && !$this->note) {
                    return true;
                } else {
                    return false;
                }
            }],
            [['activityId'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activityId' => 'id']],
        ];
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_UPDATE => ['activityId', 'stop', 'note'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activityId' => 'Activity',
            'start' => 'Start',
            'stop' => 'Stop',
            'note' => 'Note',
            'planId' => 'plan',
        ];
    }

    public function getDuration()
    {
        if ($this->_duration === null) {
            $this->_duration = $this->dateTimeStart->diff($this->dateTimeStop);
        }

        return $this->_duration;
    }

    public function getDateTimeStart()
    {
        if ($this->_dateTimeStart === null) {
            $this->_dateTimeStart = new \DateTime($this->start);
        }

        return $this->_dateTimeStart;
    }

    public function getDateTimeStop()
    {
        if ($this->_dateTimeStop === null) {
            $this->_dateTimeStop = new \DateTime($this->stop);
        }

        return $this->_dateTimeStop;
    }

    public function getTimestampStart()
    {
        if ($this->_timestampStart === null) {
            $this->_timestampStart = $this->dateTimeStart->getTimestamp();
        }

        return $this->_timestampStart;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activityId']);
    }

    /**
     * @inheritdoc
     * @return TimeTrackQuery
     */
    public static function find()
    {
        return new TimeTrackQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if (self::find()->where(['activityId' => $this->activityId])->active()->one()) {
                $this->addError('start', 'Previous interval not finished yet');
                return false;
            }
        } else {
            $track = self::findOne($this->id);
            if ($track->stop) {
                $this->addError('stop', 'This interval has been stopped already');
                return false;
            }
        }

        return parent::beforeSave($insert);
    }
}
