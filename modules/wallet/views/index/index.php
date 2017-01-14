<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\wallet\models\Credit;
use app\modules\wallet\models\Operation;

/* @var $this yii\web\View */
/* @var $operationsProvider yii\data\ActiveDataProvider */
/* @var $creditsProvider yii\data\ActiveDataProvider */
/* @var $sumOfCredits float */

$this->title = 'Wallet';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h3>Balance: <?= (new Operation())->getBalance() ?></h3>

    <div class="container">
        <div class="row text-right">
            <p>
                <?= Html::a('Stat by tags', ['stat'], ['class' => 'btn btn-default']) ?> <?= Html::a('Create Operation', ['operations/create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="row">
            <div class="credits-wrapper col-xs-12 col-md-6">
                <h4>Debts</h4>
                <?= GridView::widget([
                    'dataProvider' => $creditsProvider,
                    'rowOptions'=> function($model) {
                        /** @var Credit $model */
                        $due = new DateTime($model['dueDate']);
                        $now = new DateTime();

                        if (($due > $now) && ($now->diff($due)->days < Credit::WARNING_NUM_DAYS)) {
                            return ['class' => 'warning'];
                        } elseif ($now >= $due) {
                            return ['class' => 'danger'];
                        }

                        return null;
                    },
                    'showFooter' => true,
                    'footerRowOptions' => ['class' => 'active'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'creditor',
                            'footer' => 'Total:',
                        ],
                        [
                            'attribute' => 'sum',
                            'format' => 'decimal',
                            'footer' => number_format($sumOfCredits),
                        ],
                        [
                            'attribute' => 'dueDate',
                            'format' => ['dateTime', 'php:d.m.Y']
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}',
                            'controller' => 'credit',
                            /*'buttons' => [
                                'update' => function ($url, $model, $key) {

                                    $url = Yii::$app->urlManager->createUrl([
                                        '/wallet/operations/update',
                                        'id' => $model['operationId'],
                                    ]);
                                    $options = array_merge([
                                        'title' => Yii::t('yii', 'Update'),
                                        'aria-label' => Yii::t('yii', 'Update'),
                                        'data-pjax' => '0',
                                    ]);
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                                },
                            ],*/
                        ],
                    ],
                ]); ?>
            </div>
            <div class="operations-wrapper col-xs-12 col-md-6">
                <h4>History</h4>
                <?= GridView::widget([
                    'dataProvider' => $operationsProvider,
                    'rowOptions'=>function($model){
                        if ($model['returned'] === '0') {//can be the null
                            return ['class' => 'danger'];
                        }
                        if ($model['isSalary']) {
                            return ['class' => 'success'];
                        }
                    },
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'sum',
                            'contentOptions' => function ($model) {

                                $color = $model['sum'] < 0 ? 'red' : 'green';
                                return ['style' => 'color: ' . $color . ';'];
                            },
                            'format' => 'decimal',
                        ],
                        'description',
                        [
                            'attribute' => 'updated_at',
                            'format' => ['dateTime', 'php:d.m.Y']
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update}',
                            'controller' => 'operations',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
