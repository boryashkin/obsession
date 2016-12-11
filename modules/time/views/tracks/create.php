<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\time\models\TimeTrack */

$this->title = 'Create Time Track';
$this->params['breadcrumbs'][] = ['label' => 'Time Tracks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-track-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
