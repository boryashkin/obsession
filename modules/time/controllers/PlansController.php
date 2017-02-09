<?php

namespace app\modules\time\controllers;

use app\modules\reading\models\Reading;
use app\modules\time\models\Task;
use app\modules\time\models\TimeTrack;
use Yii;
use app\modules\time\models\Plan;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlansController implements the CRUD actions for Plan model.
 */
class PlansController extends Controller
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
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Plan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Plan::find()->with('timeTracks'),
        ]);
        $todayTaskProvider = new ActiveDataProvider([
            'query' => Task::find()->where('start >= CURDATE()')
                ->andWhere('start < DATE_ADD(CURDATE(), INTERVAL 1 DAY)'),
        ]);
        $yesterdayTaskProvider = new ActiveDataProvider([
            'query' => Task::find()->where('start >= DATE_ADD(CURDATE(), INTERVAL -2 DAY)')
                ->andWhere('start < DATE_ADD(CURDATE(), INTERVAL -1 DAY)'),
        ]);
        $tomorrowTaskProvider = new ActiveDataProvider([
            'query' => Task::find()->where('start >= DATE_ADD(CURDATE(), INTERVAL 1 DAY)')
                ->andWhere('start < DATE_ADD(CURDATE(), INTERVAL 2 DAY)'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'todayTaskProvider' => $todayTaskProvider,
            'yesterdayTaskProvider' => $yesterdayTaskProvider,
            'tomorrowTaskProvider' => $tomorrowTaskProvider,
        ]);
    }

    /**
     * Displays a single Plan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $taskProvider = new ActiveDataProvider([
            'query' => Task::find()->where(['planId' => $id]),
        ]);
        $readingsProvider = new ActiveDataProvider([
            'query' => Reading::find()->where(['planId' => $id]),
        ]);
        $timeProvider = new ActiveDataProvider([
            'query' => TimeTrack::find()->where(['planId' => $id]),
        ]);
        $sumTime = TimeTrack::find()->getSumSeconds()->where(['planId' => $id])->scalar();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'taskProvider' => $taskProvider,
            'readingsProvider' => $readingsProvider,
            'timeProvider' => $timeProvider,
            'sumTime' => $sumTime,
        ]);
    }

    /**
     * Creates a new Plan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Plan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Plan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Plan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Plan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
