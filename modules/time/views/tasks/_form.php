<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\time\models\Plan;

/* @var $this yii\web\View */
/* @var $model app\modules\time\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'start')->widget(\kartik\datetime\DateTimePicker::class, [
        'options' => [
            'class' => 'form-control input-sm',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss',
        ]
    ]) ?>

    <?= $form->field($model, 'state')->dropDownList(\app\modules\time\models\Task::STATES) ?>

    <?= $form->field($model, 'planId')
        ->dropDownList(
            Plan::find()->where('completeness < 100')->select(['name'])->asArray()->indexBy('id')->column(),
            ['prompt' => '']
        ) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
