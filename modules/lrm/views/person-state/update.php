<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\lrm\models\PersonState */

$this->title = 'Update Person State: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Person States', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="person-state-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
