<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\reading\models\Reading */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Readings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reading-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'category',
            'name',
            'link:ntext',
            'done',
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
