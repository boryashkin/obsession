<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\reading\models\Reading */

$this->title = 'Create Reading';
$this->params['breadcrumbs'][] = ['label' => 'Readings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reading-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
