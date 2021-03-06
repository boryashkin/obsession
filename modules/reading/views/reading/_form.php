<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\time\models\Plan;

/* @var $this yii\web\View */
/* @var $model app\modules\reading\models\Reading */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reading-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'planId')
        ->dropDownList(
            Plan::find()->where('completeness < 100')->select(['name'])->asArray()->indexBy('id')->column(),
            ['prompt' => '']
        )?>

    <?= $form->field($model, 'done')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
