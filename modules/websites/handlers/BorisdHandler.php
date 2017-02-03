<?php

namespace app\modules\websites\handlers;

use app\config\myown\FtpBegetAuth;
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
        if (!class_exists(FtpPusher::class)) {
            return;
        }

        $needPush = false;
        /** @var Operation $operation */
        $operation = $event->sender;
        foreach (self::$foodStatFields as $name) {
            if (false !== strpos($operation->description, $name)) {
                $needPush = true;
            }
        }
        if (!$needPush) {
            return;
        }

        /**
         * Send stat json file to other web-site's server
         */
        $auth = FtpBegetAuth::class;
        $stat = (new OperationQuery(Operation::class))->getStatByNames(self::$foodStatFields);
        $stat['tea'] *= 25;
        $stat['egg'] *= 10;
        $stat['dateEnd'] = date('d.m.Y');
        $ftp = new FtpPusher($auth::$host, $auth::$login, $auth::$password);

        $ftp->addFromString(json_encode($stat), '/json/', 'food.json');
        $ftp->push();

        \Yii::trace('BorisHandler event executed');
    }
}
