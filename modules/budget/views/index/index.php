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

$this->title = 'Budget';
$this->params['breadcrumbs'][] = $this->title;

$js = <<<'JS'
$('.toggle-done').click(function () {
    var $this = $(this),
        id = $this.parent().data('key');
    $.ajax({
        type: "POST",
        url: '/budget/ajax/toggle-done?budgetId=' + id,
        data: {"budgetId":id},
        success: function (response) {
            if (response.status) {
                if (response.value) {
                    $this.css('color', 'green');
                    $this.html('Yes');
                    $this.parent().css('opacity', 0.5);
                } else {
                    $this.css('color', 'red');
                    $this.html('No');
                    $this.parent().css('opacity', 1);
                }
            }
        },
        dataType: 'json'
    });
});
JS;

$this->registerJs($js);
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
        'rowOptions'=> function($model) {
            /** @var \app\modules\budget\models\Budget $model */
            if ($model->done) {
                return ['style' => 'opacity: 0.5'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'contentOptions' => function ($model) {
                    $weight = $model['expectedSum'] > 0 ? 'bold' : 'inherit';
                    return ['style' => "font-weight: {$weight};"];
                },
            ],
            /*[
                // if expected date has been changed
                'attribute' => 'firstExpectedDate',
                'format' => ['dateTime', 'php:d.m.Y']
            ],*/
            [
                'attribute' => 'expectedDate',
                'label' => 'Month',
                'format' => ['dateTime', 'php:F Y'],
                'contentOptions' => function ($model) {
                    $month = (new DateTime($model->expectedDate))->format('m');
                    if (in_array($month, ['12', '01', '02'])) {
                        // winter
                        $color = '#f0f8ff';
                    } elseif ($month > 2 && $month < 6) {
                        // spring
                        $color = '#f5f5dc';
                    } elseif ($month > 5 && $month < 9) {
                        // summer
                        $color = '#90ee90';
                    } else {
                        // fall
                        $color = '#faebd7';
                    }

                    return ['style' => 'background: ' . $color . ';'];
                },
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
                    return ['style' => "color: {$color};"];
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
                        'class' => 'toggle-done'
                    ];
                },
                'footer' => $totalDone || $totalUndone ? round(($totalDone / ($totalDone + $totalUndone) * 100)) . '%' : ''
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
