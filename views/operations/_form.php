<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Operation */
/* @var $credit app\models\Credit */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS

$('')

JS;
$this->registerJs($js);
?>

<div class="operation-form">

    <div>
        <?= var_dump($model->errors) ?>
        <?= var_dump($credit->errors) ?>
    </div>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary')->checkbox() ?>

    <?= $form->field($model, 'isCredit')->checkbox() ?>

    <div id="credit-form">
        <?= $form->field($credit, 'returned')->checkbox() ?>

        <?= $form->field($credit, 'dueDate')->textInput() ?>

        <?= $form->field($credit, 'creditor')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
