<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Readings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reading-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest) : ?>
        <p>
            <?= Html::a('Create Reading', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'category',
            'name',
            [
                'attribute' => 'link',
                'value' => function ($model) {
                    return Html::a('Go read', $model->link);
                },
                'format' => 'raw',
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
            ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
