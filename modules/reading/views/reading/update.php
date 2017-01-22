<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\reading\models\Reading */

$this->title = 'Update Reading: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Readings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="reading-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
