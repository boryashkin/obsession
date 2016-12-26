<?php

namespace app\modules\wallet\controllers;

use app\modules\wallet\models\Credit;
use Yii;
use app\modules\wallet\models\Operation;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $operationsProvider = new ActiveDataProvider([
            'query' => Operation::find()->withCredits(),
            'sort' => new Sort([
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ]),
            'pagination' => false,
        ]);
        $sumOfCredits = Credit::find()->sumOfCredits();
        $creditsProvider = new ActiveDataProvider([
            'query' => Credit::find()->where(['returned' => false])
                ->withOperationId()->orderBy(['dueDate' => SORT_ASC]),
            'sort' => new Sort([
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ]),
        ]);

        return $this->render('index', compact('operationsProvider','creditsProvider','sumOfCredits'));
    }

    public function actionStat()
    {
        $provider = new ArrayDataProvider([
            'allModels' => Operation::find()->getTagTotals(),
            'pagination' => false,
        ]);

        return $this->render('stat', compact('provider'));
    }
}
