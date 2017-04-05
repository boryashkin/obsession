<?php

namespace app\modules\budget\controllers;

use app\modules\budget\models\Budget;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AjaxController extends Controller
{
    public $enableCsrfValidation = false;
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
                    'toggle-done' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $budgetId
     * @return Budget
     */
    public function actionToggleDone($budgetId)
    {
        $budget = $this->getModel($budgetId);
        $done = $budget->toggleDoneStatus();
        $status = $budget->save();

        return json_encode([
            'status' => $status,
            'value' => $done,
            'errors' => $budget->getErrors(),
        ]);
    }

    /**
     * @param $id
     * @return Budget
     * @throws NotFoundHttpException
     */
    private function getModel($id)
    {
        $model = Budget::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Budget does not exist');
        }

        return $model;
    }
}