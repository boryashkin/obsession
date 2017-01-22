<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $sumOfExpectedSum float */
/* @var $sumOfRealSum float */
/* @var $totalDone float */
/* @var $totalUndone float */
/* @var $totalExpectedIncome float */
/* @var $totalExpectedOutgo float */
/* @var $totalRealIncome float */
/* @var $totalRealOutgo float */

$this->title = 'Budgets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Wallet', ['/wallet'], ['class' => 'btn btn-default']) ?> <?= Html::a('Create Budget', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <h3>Expected:
        <label class="label label-success"><?= number_format($totalExpectedIncome) ?></label>
        / <label class="label label-danger"><?= number_format($totalExpectedOutgo) ?></label>
    </h3>
    <h3>Real:
        <label class="label label-success"><?= number_format($totalRealIncome) ?></label>
        / <label class="label label-danger"><?= number_format($totalRealOutgo) ?></label>
    </h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            /*[
                'attribute' => 'firstExpectedDate',
                'format' => ['dateTime', 'php:d.m.Y']
            ],*/
            [
                'attribute' => 'expectedDate',
                'label' => 'Month',
                'format' => ['dateTime', 'php:F Y'],
            ],
            [
                'attribute' => 'expectedDate',
                'format' => ['dateTime', 'php:d.m.Y'],
            ],
            [
                'attribute' => 'realDate',
                'format' => ['dateTime', 'php:d.m.Y'],
                'footer' => 'Total:',
            ],
            [
                'attribute' => 'expectedSum',
                'format' => 'decimal',
                'contentOptions' => function ($model) {

                    $color = $model['expectedSum'] < 0 ? 'red' : 'green';
                    return ['style' => 'color: ' . $color . ';'];
                },
                'footer' => number_format($sumOfExpectedSum),
            ],
            [
                'attribute' => 'realSum',
                'format' => 'decimal',
                'contentOptions' => function ($model) {

                    $color = $model['realSum'] < 0 ? 'red' : 'green';
                    return ['style' => 'color: ' . $color . ';'];
                },
                'footer' => number_format($sumOfRealSum),
            ],
            [
                'attribute' => 'done',
                'value' => function ($model) {
                    return $model->done ? 'Yes' : 'No';
                },
                'contentOptions' => function ($model) {
                    return [
                        'style' => 'color: ' . ($model->done ? 'green' : 'red'),
                    ];
                },
                'footer' => round(($totalDone / ($totalDone + $totalUndone) * 100)) . '%'
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
