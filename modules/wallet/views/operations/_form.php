<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use app\modules\wallet\models\Credit;
use yii\db\Expression;
use app\helpers\DateHelper;
use app\modules\wallet\models\Category;

/* @var $this yii\web\View */
/* @var $model app\modules\wallet\models\Operation */
/* @var $credit app\modules\wallet\models\Credit */
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
//Submit the form by ctrl+enter
$('#w0').keydown(function (e) {
    if (e.keyCode === 13 && e.ctrlKey) {   
        e.preventDefault();
	    if (confirm('Сохранить?')) {
	        $(this).submit();
	    }
        return false;
    }
});
$('#operation-sum').focus();
JS;
$this->registerJs($js);

$newCreditStatus = '';
$oldCreditStatus = '';
if (!$model->isNewRecord && $model->isCredit) {
    $oldCreditStatus = ' active';
} else {
    $newCreditStatus = ' active';
}
?>
<div class="latest-operations">
    <?php $latestPurchases = \app\modules\wallet\models\Operation::find()->orderBy(['id' => SORT_DESC])->limit(3)->all() ?>
    <?php foreach ($latestPurchases as $purchase) : ?>
        <?php if ($model->id == $purchase->id) {continue;} ?>
        <div>
            <?= Html::a(
                date('d-m-Y H:i:s', $purchase->updated_at) . ' | ' . mb_substr($purchase->description, 0, 100),
                ['/wallet/operations/update', 'id' => $purchase->id],
                [
                    'class' => 'alert-' . ($purchase->sum < 0 ? 'warning' : 'success'),
                    'target' => '_blank',
                ]
            ) ?>
        </div>
    <?php endforeach; ?>
</div>
<br>
<div class="operation-form">

    <div>
        <?php if ($model->hasErrors()) :?>
            <div class="alert alert-danger"><?= Html::errorSummary($model) ?></div>
        <?php endif; ?>
        <?php if ($credit->hasErrors()) : ?>
            <div class="alert alert-danger"><?= Html::errorSummary($credit) ?></div>
        <?php endif; ?>
    </div>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isCredit')->checkbox() ?>

    <!-- Debt values -->
    <div id="credit-form"<?php if (!$model->isCredit) : ?> style="display: none;"<?php endif; ?>>
        <ul class="nav nav-pills" role="tablist">
            <li role="presentation" class="<?= $newCreditStatus ?>"><a href="#new" aria-controls="new" role="tab" data-toggle="tab">New</a></li>
            <li role="presentation" class="<?= $oldCreditStatus ?>"><a href="#existing" aria-controls="existing" role="tab" data-toggle="tab">Existing</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane<?= $newCreditStatus ?>" id="new">
                <?= $form->field($credit, 'dueDate')->widget(DateTimePicker::class, [
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

                <?= $form->field($credit, 'creditor')->textInput(['maxlength' => true]) ?>
            </div>
            <div role="tabpanel" class="tab-pane<?= $oldCreditStatus ?>" id="existing">
                <?= $form->field($model, 'toCreditId')->dropDownList(Credit::find()->select(['creditor', 'id'])->indexBy('id')->asArray()->column(), ['prompt'=>'Creditor']) ?>

                <?= $form->field($credit, 'returned')->checkbox() ?>
            </div>
        </div>


    </div>
    <!-- //Debt values -->

    <?= $form->field($model, 'categoryId')->dropDownList(
        Category::find()->select('name')->indexBy('id')->column(),
        ['prompt' => '']
    ) ?>

    <?= $form->field($model, 'tagsArray')->widget(\kartik\select2\Select2::class, [
        'initValueText' => '', // set the initial display text
        'options' => [
            'placeholder' => 'Tags',
            'multiple' => true,
        ],
        'data' => \app\models\Tag::getDropdownList()
    ]); ?>

    <?= $form->field($model, 'budgetId')->dropDownList(
            \app\modules\budget\models\Budget::find()->select(new Expression('CONCAT(expectedDate, " ", name)'))
                ->where(['<=', 'expectedDate', DateHelper::getEndOfMonth(new DateTime())->format('Y-m-d H:i:s')])
                ->andWhere(['>=', 'expectedDate', DateHelper::getStartOfMonth(new DateTime())->format('Y-m-d H:i:s')])
                ->indexBy('id')->column(),
        ['prompt' => '']
    ) ?>

    <?= Html::a('Add tag', Yii::$app->urlManager->createUrl('/tags/create'), [
        'target' => '_blank',
    ]) ?>

    <div class="form-group text-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
