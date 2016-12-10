<?php

namespace app\modules\wallet\controllers;

use app\modules\wallet\models\Credit;
use Yii;
use app\modules\wallet\models\Operation;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Wallet quick view
 */
class IndexController extends Controller
{
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
        ]);
        $creditsProvider = new ActiveDataProvider([
            'query' => Credit::find()->where(['returned' => false])->withOperationId(),
            'sort' => new Sort([
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ]),
        ]);

        return $this->render('index', [
            'operationsProvider' => $operationsProvider,
            'creditsProvider' => $creditsProvider,
        ]);
    }
}
