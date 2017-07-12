<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\lrm\models\PersonState */

$this->title = 'Create Person State';
$this->params['breadcrumbs'][] = ['label' => 'Person States', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-state-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
