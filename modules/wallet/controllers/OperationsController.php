<?php

namespace app\modules\wallet\controllers;

use app\modules\wallet\models\Credit;
use Yii;
use app\modules\wallet\models\Operation;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperationsController implements the CRUD actions for Operation model.
 */
class OperationsController extends Controller
{
    /**
     * @inheritdocz 
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
     * Lists all Operation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Operation::find()->withCredits(),
            'sort' => new Sort([
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Operation model.
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
     * Creates a new Operation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Operation();
        $credit = new Credit();

        $isLoaded = $model->load(Yii::$app->request->post());
        if ($model->isCredit && $credit->load(Yii::$app->request->post())) {
            if (!$model->toCreditId) {
                //new credit
                $model->credit = $credit;
            } else {
                //link to existing credit
                $credit = Credit::findOne($model->toCreditId);
                if (!$credit) {
                    $model->addError('toCreditId', 'Credit does not exist');
                } else {
                    $model->creditId = $credit->id;
                }
            }

        }
        if ($isLoaded && $model->save()) {
            return $this->redirect(['']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'credit' => $credit,
            ]);
        }
    }

    /**
     * Updates an existing Operation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $isLoaded = $model->load(Yii::$app->request->post());
        if ($isLoaded && $model->isCredit) {
            $credit = new Credit();
            if (!$model->toCreditId && $credit->load(Yii::$app->request->post())) {
                //new credit
                $model->credit = $credit;
            } else {
                //link to existing credit
                $credit = Credit::findOne($model->toCreditId);
                if (!$credit) {
                    $model->addError('toCreditId', 'Credit does not exist');
                } else {
                    $model->creditId = $credit->id;
                }
            }
        } else {
            $credit = new Credit();
        }

        if ($isLoaded && $model->save()) {
            return $this->redirect(['/wallet']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'credit' => $credit,
            ]);
        }
    }

    /**
     * Deletes an existing Operation model.
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
     * Finds the Operation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Operation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Operation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
