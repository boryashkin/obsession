<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\wallet\models\Credit */
/* @var $operations \app\modules\wallet\models\Operation[] */

$this->title = 'Update Credit: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Credits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="credit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <div class="row">
            <h3>Operations of this credit</h3>
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $operations,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'sum',
                    'description',
                    [
                        'attribute' => 'updated_at',
                        'format' => ['dateTime']
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'controller' => 'operations',
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
