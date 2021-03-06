<?php

namespace app\modules\websites\handlers;

use Yii;
use app\components\FtpPusher;
use app\modules\wallet\models\Operation;
use app\modules\wallet\models\OperationQuery;
use yii\base\Event;

/**
 * Обработчик событий, влияющих на borisd
 * // It's needs an ftp credentials
 *
 * @package app\modules\websites\handlers
 */
class BorisdHandler
{
    /** @var array array with data which need to be pushed */
    public static $foodStatFields = [
        'tea' => 'чай',
        'bread' => 'хлеб',
        'ketchup' => 'кетчуп',
        'egg' => 'яйц',
    ];

    /**
     * Выложить json со статой еды
     * работает, если в description операции есть слово из $foorStatFields
     * @param Event $event
     */
    public static function pushFoodStat(Event $event)
    {
        \Yii::trace('BorisHandler event called');
        if (!class_exists(FtpPusher::class)) {
            \Yii::trace('BorisHandler event fail FtpPusher not found');
            return;
        }

        $needPush = false;
        /** @var Operation $operation */
        $operation = $event->sender;
        foreach (self::$foodStatFields as $name) {
            if (false !== mb_stripos($operation->description, $name)) {
                $needPush = true;
            }
        }
        if (!$needPush) {
            \Yii::trace('BorisHandler event fail no need to push');
            return;
        }

        /**
         * Send stat json file to other web-site's server
         */
        $stat = (new OperationQuery(Operation::class))->getStatByNames(self::$foodStatFields);
        $stat['tea'] *= 25;
        $stat['egg'] *= 10;
        $stat['dateEnd'] = date('d.m.Y');
        $ftp = new FtpPusher(
            Yii::$app->params['borisd.ftp']['host'],
            Yii::$app->params['borisd.ftp']['login'],
            Yii::$app->params['borisd.ftp']['password']
        );

        $ftp->addFromString(json_encode($stat), '/json/', 'food.json');
        $ftp->push();

        \Yii::trace('BorisHandler event executed');
    }
}
