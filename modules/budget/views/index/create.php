<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\budget\models\Budget */

$this->title = 'Create Budget';
$this->params['breadcrumbs'][] = ['label' => 'Budgets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
