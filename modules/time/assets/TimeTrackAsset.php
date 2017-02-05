<?php

namespace app\modules\time\assets;

use yii\web\AssetBundle;

class TimeTrackAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/time/assets';

    public $js = [
        'js/timeTrack.js',
    ];
    public $css = [
        'css/timeTrack.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}