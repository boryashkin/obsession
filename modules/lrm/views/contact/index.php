<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\lrm\models\search\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Contact', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'personId',
                'format' => 'html',
                'value' => function (\app\modules\lrm\models\Contact $model) {
                    return Html::a($model->person->fullName, ['/lrm/person/view', 'id' => $model->personId]);
                },
                'filter' => \app\modules\lrm\models\Person::find()->select('fullName')->indexBy('id')->column(),
            ],
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
            // 'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
