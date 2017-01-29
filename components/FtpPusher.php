<?php

namespace app\components;
use yii\base\Exception;
use yii\web\UploadedFile;

/**
 * Set as component?
 *
 * Class FtpPusher
 * @package app\components
 */
class FtpPusher
{
    const LOCAL_STORAGE = __DIR__ . '/pushed/';

    private $host;
    private $login;
    private $password;
    private $connection;

    private $files = [
        //[0 => filename
        //1 => localpath
        //2 => remotepath
        //3 => uploaded?]
    ];

    public function __construct($host, $login, $password)
    {
        $this->login = $login;
        $this->host = $host;
        $this->password = $password;
    }

    public function connectProcedure()
    {
        if (!$this->connect()) {
            throw new Exception('Connection failed');
        }
        if (!$this->login()) {
            $this->disconnect();
            throw new Exception('Authorization failed');
        }
    }

    private function connect()
    {
        $this->connection = ftp_connect($this->host, 21, 30);

        return $this->connection !== false;
    }
    private function disconnect()
    {
        ftp_close($this->connection);
        $this->connection = null;
    }

    private function login()
    {
        return @ftp_login($this->connection, $this->login, $this->password);
    }

    public function push()
    {
        $this->connectProcedure();

        $response = true;
        foreach ($this->files as $key => $file) {
            if ($file[3]) {
                //already uploaded
                continue;
            }
            if (ftp_put($this->connection, $file[2] . $file[0], $file[1] . $file[0], FTP_ASCII)){
                $this->files[$key][3] = true;
            } else {
                $this->files[$key][3] = false;
                $response = false;
            }
        }

        $this->disconnect();

        return $response;
    }

    public function addFromString($content, $remotePath, $name)
    {
        if (file_put_contents(self::LOCAL_STORAGE . $name, $content)) {
            $this->files[] = [
                $name,
                self::LOCAL_STORAGE,
                $remotePath,
                false,
            ];

            return true;
        }

        return false;
    }
}