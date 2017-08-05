<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\lrm\models\search\InteractionNoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Interaction Notes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interaction-note-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Interaction Note', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'person.fullName',
            'text:ntext',
            'appraisal',
            'date',
            // 'createdAt',
            // 'updatedAt',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
