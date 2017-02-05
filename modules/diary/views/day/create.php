<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\diary\models\Diary */

$this->title = 'Add record';
$this->params['breadcrumbs'][] = ['label' => 'Diary', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diary-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
