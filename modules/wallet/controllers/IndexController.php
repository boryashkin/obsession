<?php

namespace app\modules\wallet\controllers;

use app\helpers\DateHelper;
use app\modules\wallet\helpers\Wallet;
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
        //preparing dataProviders
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

        //Initializing totals
        $today = new \DateTime();
        $tomorrow = (new \DateTime())->modify('+1 day');
        $statForm = new StatSearchForm([
            'dateFrom' => DateHelper::getStartOfWeek($today)->format('Y-m-d'),
            'dateTo' => $tomorrow->format('Y-m-d'),
        ]);
        $weekTotal = $statForm->searchTotalExpensesQuery()->scalar();
        $statForm->dateFrom = DateHelper::getStartOfMonth($today)->format('Y-m-d');
        $monthTotal = $statForm->searchTotalExpensesQuery()->scalar();

        $expectedMonthExpenses = Wallet::getExpectedExpensesSumForMonth($today);
        $expectedMonthIncome = Wallet::getExpectedIncomeSumForMonth($today);

        //budgets
        $budgetExpenses = $statForm->searchExpensesByBudget()->asArray()->all();

        return $this->render('index', compact(
            'operationsProvider',
            'creditsProvider',
            'sumOfCredits',
            'dailyProvider',
            'weekTotal',
            'monthTotal',
            'expectedMonthExpenses',
            'expectedMonthIncome',
            'budgetExpenses'
        ));
    }

    /**
     * Expenses by tags
     * @return string
     */
    public function actionStat()
    {
        $model = new StatSearchForm();
        $model->load(Yii::$app->request->post());
        
        $provider = new ArrayDataProvider([
            'allModels' => $model->searchTagTotalsQuery()->all(),
            'pagination' => false,
        ]);

        return $this->render('stat', compact('provider', 'model'));
    }
}
