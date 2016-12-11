<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\time\models\TimeTrack */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="time-track-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'activityId')->dropDownList(\app\modules\time\models\Activity::find()->select('name')->indexBy('id')->column()) ?>

    <?= $form->field($model, 'start')->widget(DateTimePicker::class, [
        'options' => [
            'class' => 'form-control input-sm',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss',
        ]
    ]) ?>

    <?= $form->field($model, 'stop')->widget(DateTimePicker::class, [
        'options' => [
            'class' => 'form-control input-sm',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss',
        ]
    ]) ?>

    <?= $form->field($model, 'note')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
