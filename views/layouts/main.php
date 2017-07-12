<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My life',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Plans', 'url' => ['/time/plans/index']],
            ['label' => 'Wallet', 'url' => ['/wallet']],
            ['label' => 'Time', 'url' => Yii::$app->user->isGuest ? ['/time/stat'] : ['/time']],
            ['label' => 'Reading', 'url' => ['/reading']],
            ['label' => 'Diary', 'url' => ['/diary']],
            ['label' => 'Relationships', 'items' => [
                ['label' => 'Persons', 'url' => ['/lrm/person']],
                ['label' => 'Log', 'url' => ['/lrm/interaction-note']],
                ['label' => 'States', 'url' => ['/lrm/person-state']],
            ]],
            ['label' => 'Settings', 'items' => [
                ['label' => 'Tags', 'url' => ['/tags']],
                ['label' => 'Categories', 'url' => ['/wallet/category']],
                ['label' => 'Users', 'url' => ['/users']],
                ['label' => 'Activities', 'url' => ['/time/activities']],
                ['label' => 'Services', 'items' => [
                    ['label' => 'Dump db', 'url' => ['/service/db-dump']],
                ]],
            ]],
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php if (Yii::$app->session->hasFlash('result')) : ?>
            <div class="alert alert-info"><?= Yii::$app->session->getFlash('result') ?></div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Obsession <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
