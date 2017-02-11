<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\wallet\models\Credit;
use app\modules\wallet\models\Operation;

/* @var $this yii\web\View */
/* @var $provider yii\data\ArrayDataProvider */
/* @var $model \app\modules\wallet\models\StatSearchForm */

$this->title = 'Stat by tags';
$this->params['breadcrumbs'][] = ['label' => 'Wallet', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <div class="row">
            <div class="credits-wrapper col-xs-12 col-md-6">
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'enableClientValidation' => false,
                ]); ?>
                <div>
                    <div class="row">
                        <div class="credits-wrapper col-xs-12 col-md-6">
                            <?= $form->field($model, 'dateFrom')->widget(\kartik\datetime\DateTimePicker::class, [
                                'options' => [
                                    'class' => 'form-control input-sm',
                                ],
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'yyyy-mm-dd',
                                    'maxView' => 3,
                                    'minView' => 2,
                                ]
                            ]) ?>
                        </div>
                        <div class="credits-wrapper col-xs-12 col-md-6">
                            <?= $form->field($model, 'dateTo')->widget(\kartik\datetime\DateTimePicker::class, [
                                'options' => [
                                    'class' => 'form-control input-sm',
                                ],
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'yyyy-mm-dd',
                                    'maxView' => 3,
                                    'minView' => 2,
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="credits-wrapper col-xs-12 col-md-6"></div>
                        <div class="credits-wrapper col-xs-12 col-md-6 text-right">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
                <?php $form->end() ?>

                <?= GridView::widget([
                    'dataProvider' => $provider,

                    'showFooter' => true,
                    'footerRowOptions' => ['class' => 'active'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'name',
                        ],
                        [
                            'attribute' => 'sum',
                            'contentOptions' => function ($model) {

                                $color = $model['sum'] < 0 ? 'red' : 'green';
                                return ['style' => 'color: ' . $color . ';'];
                            },
                            'format' => 'decimal',
                        ],

                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
