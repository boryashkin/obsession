<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\budget\models\Budget */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budget-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'expectedDate')->widget(DateTimePicker::class, [
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

    <?php if (!$model->isNewRecord) : ?>
        <?= $form->field($model, 'realDate')->widget(DateTimePicker::class, [
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
    <?php endif; ?>

    <?= $form->field($model, 'expectedSum')->textInput(['maxlength' => true]) ?>

    <?php if (!$model->isNewRecord) : ?>
        <?= $form->field($model, 'realSum')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'done')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
