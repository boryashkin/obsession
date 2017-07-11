<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\lrm\models\InteractionNote */

$this->title = 'Update Interaction Note: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Interaction Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="interaction-note-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
