<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\wallet\helpers\Wallet;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Operations';
$this->params['breadcrumbs'][] = ['label' => 'Wallet', 'url' => ['/wallet']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h3>На счету: <?= Wallet::getBalance() ?></h3>

    <p>
        <?= Html::a('Create Operation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions'=>function($model){
            if ($model['returned'] === '0') {
                return ['class' => 'danger'];
            }
            if ($model['isSalary']) {
                return ['class' => 'success'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sum',
            'description',
            'isSalary:boolean',
            'credit.dueDate',
            // 'created_at',
            [
                'attribute' => 'updated_at',
                'format' => ['dateTime']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
