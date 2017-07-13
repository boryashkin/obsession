<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\lrm\models\search\ContactSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'personId')->dropDownList(\app\modules\lrm\models\Person::find()->select('fullName')->indexBy('id')->column()) ?>

    <?= $form->field($model, 'contact') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
