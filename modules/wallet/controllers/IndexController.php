<?php

namespace app\modules\wallet\controllers;

use app\modules\wallet\models\Credit;
use app\modules\wallet\models\StatSearchForm;
use Yii;
use app\modules\wallet\models\Operation;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
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
        $dailyProvider = new ArrayDataProvider([
            'allModels' => Operation::find()->getDailyStat()
                ->orderBy(['id' => SORT_DESC])
                ->limit(15)
                ->all(),
            'pagination' => false,
        ]);

        return $this->render('index', compact(
            'operationsProvider',
            'creditsProvider',
            'sumOfCredits',
            'dailyProvider'
        ));
    }

    public function actionStat()
    {
        $model = new StatSearchForm();
        $model->load(Yii::$app->request->post());
        
        $provider = new ArrayDataProvider([
            'allModels' => $model->searchQuery()->all(),
            'pagination' => false,
        ]);

        return $this->render('stat', compact('provider', 'model'));
    }
}
