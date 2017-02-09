<?php

namespace app\modules\service\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class DbDumpController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Mock up function
     * @return bool
     */
    public function actionIndex()
    {
        $filename = Yii::getAlias('@runtime'). '/';
        $filename .= 'account' . date('Y-m-d--H-i-s') . '.sql';
        $user = Yii::$app->params['dbdump']['mysql']['username'];
        $pass = Yii::$app->params['dbdump']['mysql']['password'];
        $db = Yii::$app->params['dbdump']['mysql']['db'];

        $response = shell_exec("mysqldump --databases {$db} --user={$user} --password={$pass}> {$filename}");

        //@todo: write some test for execute this sql and import it as _test db and unnit-tests for that too
        if (file_exists($filename) && filesize($filename)) {
            Yii::$app->session->setFlash('result', "Successfully dumped {$filename}");
        } else {
            Yii::$app->session->setFlash('result', 'Dump failed');
        }

        $this->redirect(['/']);
    }
}