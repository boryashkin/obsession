<?php

/* @var array $config */
$config = \yii\helpers\ArrayHelper::merge($config, [
    'modules' => [
        'wallet' => [
            'on beforeAction' => function ($event) {
                /** @var $event \yii\base\ActionEvent */
                if (
                    $event->action->id == 'create'
                    && $event->action->controller instanceof \app\modules\wallet\controllers\OperationsController
                ) {
                    // если операция создается, то нужно залить
                    if (class_exists('\app\modules\websites\handlers\BorisdHandler')) {
                        \yii\base\Event::on(
                            \app\modules\wallet\models\Operation::class,
                            \app\modules\wallet\models\Operation::EVENT_AFTER_INSERT,
                            [\app\modules\websites\handlers\BorisdHandler::class, 'pushFoodStat']);
                        Yii::trace('BorisHandler event applied');
                    }
                }
            }
        ],
    ],
]);
