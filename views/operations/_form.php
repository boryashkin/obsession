<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Operation */
/* @var $credit app\models\Credit */
/* @var $form yii\widgets\ActiveForm */
$js = <<<'JS'

$('#operation-iscredit').change(function() {
  var $this = $(this);
  
  if ($this.prop('checked')) {
    $('#credit-form').show();
  } else {
    $('#credit-form').hide();
    $('#credit-form').find('input').val(null);
  }
});

JS;
$this->registerJs($js);
?>

<div class="operation-form">

    <div>
        <?php if ($model->hasErrors()) :?><?php var_dump($model->errors) ?> <?php endif; ?>
        <?php if ($credit->hasErrors()) : ?><?php var_dump($credit->errors) ?><?php endif; ?>
    </div>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'salary')->checkbox() ?>

    <?= $form->field($model, 'isCredit')->checkbox() ?>

    <div id="credit-form"<?php if (!$model->isCredit) : ?> style="display: none;"<?php endif; ?>>
        <?= $form->field($credit, 'dueDate')->widget(DateTimePicker::class, [
            'options' => [
                'class' => 'form-control input-sm',
            ],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd.mm.yyyy',
            ]
        ]) ?>

        <?= $form->field($credit, 'creditor')->textInput(['maxlength' => true]) ?>

        <?= $form->field($credit, 'returned')->checkbox() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
