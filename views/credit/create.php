<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Credit */

$this->title = 'Create Credit';
$this->params['breadcrumbs'][] = ['label' => 'Credits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
