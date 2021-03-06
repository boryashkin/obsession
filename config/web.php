<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'SIwBtN3uLrqKisVQQysTrGFB4G9azto9',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/time/stat' => '/time/index/stat',
            ],
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
    ],
    'modules' => [
        'wallet' => [
            'class' => 'app\modules\wallet\Module',
        ],
        'time' => [
            'class' => 'app\modules\time\Module',
        ],
        'reading' => [
            'class' => 'app\modules\reading\Module',
        ],
        'budget' => [
            'class' => 'app\modules\budget\Module',
        ],
        'diary' => [
            'class' => 'app\modules\diary\Module',
        ],
        'service' => [
            'class' => 'app\modules\service\Module',
        ],
        'lrm' => [
            'class' => 'app\modules\lrm\Module',
        ],
    ],
    'params' => $params,
];

//attach some event handlers
if (@include_once __DIR__ . '/myown/config.php') {
    @include_once __DIR__ . '/myown/web.php';
}

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
