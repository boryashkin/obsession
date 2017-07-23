<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\modules\lrm\models\InteractionNote;
use app\modules\lrm\models\Contact;

/* @var $this yii\web\View */
/* @var $model app\modules\lrm\models\Person */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'fullName',
            'birthdate',
            'description',
            'gender',
            'createdAt:date',
            'updatedAt:date',
        ],
    ]) ?>

    <p>
        <?= Html::a('Add Contact', ['/lrm/contact/create', 'personId' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => Contact::find()->where(['personId' => $model->id])->orderBy('sort'),
            'pagination' => false,
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sort',
            [
                'attribute' => 'contact',
                'format' => 'raw',
                'value' => function (\app\modules\lrm\models\Contact $model) {
                    return Html::a(
                        $model::$typeLink[$model->type] . $model->contact,
                        $model::$typeLink[$model->type] . $model->contact,
                        [
                            'class' => 'asdasdas',
                            'target' => '_blank',
                        ]
                    );
                }
            ],
            'note',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => '/lrm/contact',
            ],
        ],
    ]); ?>
    <p>
        <?= Html::a('Add Interaction', ['/lrm/interaction-note/create', 'personId' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => InteractionNote::find()->where(['personId' => $model->id]),
            'pagination' => false,
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'date:date',
            [
                'attribute' => 'appraisal',
                'value' => function (InteractionNote $model) {
                    return $model->appraisal + 1;
                }
            ],
            'text',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => '/lrm/interaction-note',
            ],
        ],
    ]); ?>
</div>
