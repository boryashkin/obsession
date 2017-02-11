<?php

namespace app\modules\budget\controllers;

use app\modules\budget\models\BudgetStatistic;
use Yii;
use app\modules\budget\models\Budget;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IndexController implements the CRUD actions for Budget model.
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
     * Lists all Budget models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Budget::find(),
            'sort' => new Sort([
                'defaultOrder' => [
                    'expectedDate' => SORT_ASC,
                ],
            ]),
            'pagination' => false,
        ]);
        $sums = BudgetStatistic::getSums();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'sumOfExpectedSum' => (float)$sums['expectedSum'],
            'sumOfRealSum' => (float)$sums['realSum'],
            'totalDone' => (float)($sums['totalRecords'] - $sums['totalUndone']),
            'totalUndone' => (float)$sums['totalUndone'],
            'totalExpectedIncome' => (float)$sums['expectedIncome'],
            'totalExpectedOutgo' => (float)$sums['expectedOutgo'],
            'totalRealIncome' => (float)$sums['realIncome'],
            'totalRealOutgo' => (float)$sums['realOutgo'],
        ]);
    }

    /**
     * Displays a single Budget model.
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
     * Creates a new Budget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Budget();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Budget model.
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
     * Deletes an existing Budget model.
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
     * Finds the Budget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Budget the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Budget::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
