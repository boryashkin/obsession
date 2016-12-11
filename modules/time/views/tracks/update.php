<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\time\models\TimeTrack */

$this->title = 'Update Time Track: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Time Tracks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="time-track-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
