<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\lrm\models\InteractionNote */

$this->title = 'Create Interaction Note';
$this->params['breadcrumbs'][] = ['label' => 'Interaction Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interaction-note-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
