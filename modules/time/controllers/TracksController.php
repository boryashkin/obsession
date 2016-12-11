<?php

namespace app\modules\time\controllers;

use Yii;
use app\modules\time\models\TimeTrack;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TracksController implements the CRUD actions for TimeTrack model.
 *
 * @todo: updates for "note" field when time still going
 * @todo: refactor
 */
class TracksController extends Controller
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
     * Lists all TimeTrack models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TimeTrack::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TimeTrack model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TimeTrack model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TimeTrack();
        $model->scenario = TimeTrack::SCENARIO_CREATE;

        if (Yii::$app->request->isAjax) {
            //json
            Yii::$app->response->format = Response::FORMAT_JSON;

            $model->load(Yii::$app->request->post(), '');
            $model->start = date('Y-m-d H:i:s');
            if (!$model->hasErrors() && $model->save()) {
                return [
                    'status' => true,
                    'trackId' => $model->id,
                    'timestamp' => $model->timestampStart,
                ];
            } else {
                return [
                    'status' => false,
                    'errors' => $model->getErrors(),
                ];
            }
        } else {
            //http
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

    }

    /**
     * Updates an existing TimeTrack model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = TimeTrack::SCENARIO_UPDATE;

        if (Yii::$app->request->isAjax) {
            //json
            Yii::$app->response->format = Response::FORMAT_JSON;

            $model->load(Yii::$app->request->post(), '');
            if (isset($_POST['action']) && $_POST['action'] == 'stop') {
                $model->stop = date('Y-m-d H:i:s');
            }//or it can be addition of note

            if (!$model->hasErrors() && $model->save()) {
                return [
                    'status' => true,
                    'timestamp' => $model->timestampStart,
                ];
            } else {
                return [
                    'status' => false,
                    'errors' => $model->getErrors(),
                ];
            }
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Deletes an existing TimeTrack model.
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
     * Finds the TimeTrack model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TimeTrack the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TimeTrack::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
