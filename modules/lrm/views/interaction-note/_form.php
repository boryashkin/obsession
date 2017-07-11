<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\lrm\models\Person;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\lrm\models\InteractionNote */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interaction-note-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'personId')->dropDownList(Person::find()->select('fullName')->indexBy('id')->column()) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'appraisal')->dropDownList([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]) ?>

    <?= $form->field($model, 'date')->widget(DateTimePicker::class, [
        'options' => [
            'class' => 'form-control input-sm',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
            'maxView' => 2,
            'minView' => 3,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
