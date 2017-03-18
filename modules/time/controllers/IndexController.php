<?php

namespace app\modules\time\controllers;

use app\modules\time\models\Activity;
use app\modules\time\models\TimeTrack;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Wallet quick view
 */
class IndexController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['stat'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Operation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $activities = Activity::find()->all();

        return $this->render('index', compact('activities', 'tracks'));
    }

    /**
     * Statistics
     *
     * @return mixed
     */
    public function actionStat()
    {
        $stat = TimeTrack::find()->getStat();
        $sumTimeByAct = TimeTrack::find()->getSumSeconds()->groupBy(['activityId'])
            ->addSelect(['activityId'])->with('activity')->asArray();
        $sumTimeByAct = new ArrayDataProvider([
            'allModels' => $sumTimeByAct->all(),
        ]);
        $activeTracks = TimeTrack::find()->where(['stop' => null])->with(['activity'])->all();
        
        return $this->render('stat', compact('stat', 'sumTimeByAct', 'activeTracks'));
    }
}
