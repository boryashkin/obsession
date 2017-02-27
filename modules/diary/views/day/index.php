<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Diary';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diary-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add record', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'date',
                'format' => ['dateTime', 'php:d.m.Y']
            ],
            [
                'label' => 'Day of week',
                'value' => function ($model) {
                    return \app\helpers\DateHelper::getDayOfWeek(new DateTime($model->date));
                }
            ],
            'description:ntext',
            'rate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
