<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\wallet\models\Credit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credit-form">
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'returned')->checkbox() ?>

    <?= $form->field($model, 'dueDate')->widget(DateTimePicker::class, [
        'options' => [
            'class' => 'form-control input-sm',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
            'maxView' => 3,
            'minView' => 2,
        ]
    ]) ?>

    <?= $form->field($model, 'creditor')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
