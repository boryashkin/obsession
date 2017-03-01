<?php
/** @var $this yii\web\View */
/** @var $stat array */
/* @var $sumTimeByAct \yii\db\ActiveQuery */
//@todo: get normal dataset from db instead of handling it here
use miloschuman\highcharts\Highcharts;

$this->title = 'Daily stat';
$this->params['breadcrumbs'][] = ['label' => 'Time', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$categories = [];
$series = [];
/*
 *  array(5) {
    ["id"]=>
    string(1) "2"
    ["name"]=>
    string(14) "Трамвай"
    ["activityId"]=>
    string(1) "1"
    ["date"]=>
    string(10) "2016-12-12"
    ["sum"]=>
    string(4) "2846"
  }
 */

$activities = [];

foreach ($stat as $row) {
    $categories[$row['date']] = $row['date'];
    if (isset($series[$row['name']])) {
        $series[$row['name']]['data'][$row['date']] = round(floatval($row['sum'])/3600, 2);
    } else {
        $series[$row['name']] = [
            'name' => $row['name'],
            'data' => [
                $row['date'] => round(floatval($row['sum'])/3600, 2),
            ],
        ];
    }
}
//fill up holes with zeros
foreach ($categories as $cat) {
    foreach ($series as $name => $row) {
        if (!isset($row['data'][$cat])) {
            $series[$name]['data'][$cat] = 0;
        }
    }
}
//sort data and remove the keys
foreach ($series as $name => $row) {
    ksort($series[$name]['data']);
    $series[$name]['data'] = array_values($series[$name]['data']);
}
$categories = array_values($categories);
$series = array_values($series);
?>
<?= Highcharts::widget([
    'options' => [
        'title' => ['text' => 'Daily stat'],
        'xAxis' => [
            'categories' => $categories,
        ],
        'yAxis' => [
            //'tickInterval' => 2,
            /*'labels' => [
                'formatter' => new \yii\web\JsExpression('function(){ return Number(parseInt(this.value)/3600).toFixed(1)}'),
            ],*/
            'title' => ['text' => 'Hours']
        ],
        'series' => $series
    ]
]) ?>
<div>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $sumTimeByAct,
        'layout' => "{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'activity.name',
            [
                'label' => 'Spent hours',
                'value' => function ($model) {
                    return number_format($model['sum'] * 1 / (60 * 60), 2);
                },
            ],
        ],
    ]); ?>
</div>
<hr>
<?= \yii\bootstrap\Html::a('< Back to timers', ['index'], ['class' => 'btn btn-default']) ?>