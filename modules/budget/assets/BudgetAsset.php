<?php

namespace app\modules\budget\assets;

use yii\web\AssetBundle;

class BudgetAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/budget/assets';

    public $js = [
        'js/budget.js',
    ];

    public $css = [
        'css/budget.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}