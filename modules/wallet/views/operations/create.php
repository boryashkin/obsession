<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\wallet\models\Operation */

$this->title = 'Create Operation';
$this->params['breadcrumbs'][] = ['label' => 'Wallet', 'url' => ['/wallet']];
$this->params['breadcrumbs'][] = ['label' => 'Operations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'credit' => $credit,
    ]) ?>

</div>
