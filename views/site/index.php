<?php
/* @var $this yii\web\View */

use yii\bootstrap\Html;

$this->title = 'Obsession app';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Obsession</h1>

        <p class="lead">by control</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Money</h2>

                <p>Expense tracker.</p>

                <p><?= Html::a('Wallet', Yii::$app->urlManager->createUrl('wallet'), ['class' => 'btn btn-default']) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Time</h2>

                <p>Time management.</p>

                <p><?= Html::a('Time', Yii::$app->urlManager->createUrl('time'), ['class' => 'btn btn-default']) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Relations</h2>

                <p>Log of social interactions.</p>

                <p><?= Html::a('soon...', '#', ['class' => 'btn btn-default']) ?></p>
            </div>
        </div>

    </div>
</div>
