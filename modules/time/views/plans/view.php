<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\time\models\Plan */
/* @var $taskProvider \yii\data\ActiveDataProvider */
/* @var $readingsProvider \yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <h4>Redings</h4>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $readingsProvider,
            'layout' => "{items}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'id',
                    'value' => function ($model) {
                        /** @var \app\modules\reading\models\Reading $model */
                        return Html::a($model->id, ['/reading/reading/view', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'name',
                    'value' => function ($model) {
                        /** @var \app\modules\reading\models\Reading $model */
                        if ($model->link) {
                            return Html::a($model->name, $model->link);
                        } else {
                            return $model->name;
                        }
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'done',
                    'value' => function ($model) {
                        /** @var \app\modules\reading\models\Reading $model */
                        return $model->done ? 'Yes' : 'No';
                    },
                    'contentOptions' => function ($model) {
                        return [
                            'style' => 'color: ' . ($model->done ? 'green' : 'red'),
                        ];
                    },
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update}',
                    'controller' => 'tasks',
                ],
            ],
        ]); ?>
        <h4>Related tasks</h4>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $taskProvider,
            'layout' => "{items}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'start',
                    'value' => function ($model) {
                        /** @var \app\modules\time\models\Task $model*/
                        return (new \DateTime($model->start))->format('d.m.Y H:i');
                    },
                ],
                'duration',
                'name',
                [
                    'attribute' => 'state',
                    'value' => function ($model) {
                        /** @var \app\modules\time\models\Task $model */
                        return \app\modules\time\models\Task::STATES[$model->state];
                    },
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update}',
                    'controller' => 'tasks',
                ],
            ],
        ]); ?>
        <h4>Spended time</h4>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $timeProvider,
            'layout' => "{items}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'start',
                    'value' => function ($model) {
                        /** @var \app\modules\time\models\TimeTrack $model*/
                        return (new \DateTime($model->start))->format('d.m.Y H:i');
                    },
                ],
                [
                    'attribute' => 'stop',
                    'value' => function ($model) {
                        /** @var \app\modules\time\models\TimeTrack $model*/
                        return (new \DateTime($model->stop))->format('d.m.Y H:i');
                    },
                ],

                'note',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'controller' => 'tracks',
                ],
            ],
        ]); ?>
    </div>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            'completeness',
            [
                'attribute' => 'created_at',
                'format' => ['dateTime', 'php:d.m.Y'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['dateTime', 'php:d.m.Y'],
            ],
        ],
    ]) ?>

</div>
