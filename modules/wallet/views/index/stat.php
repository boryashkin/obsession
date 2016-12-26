<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\wallet\models\Credit;
use app\modules\wallet\models\Operation;

/* @var $this yii\web\View */
/* @var $provider yii\data\ArrayDataProvider */

$this->title = 'Stat by tags';
$this->params['breadcrumbs'][] = ['label' => 'Wallet', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <div class="row">
            <div class="credits-wrapper col-xs-12 col-md-6">
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
