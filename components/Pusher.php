<?php

namespace app\components;

/**
 * Class Pusher
 * @package app\components
 */
abstract class Pusher
{
    private $transport;

    /**
     * @todo: create interface for transport
     * Pusher constructor.
     * @param $transport
     */
    public function __construct($transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param $content
     * @param $name
     * @param $extension
     * @return mixed
     */
    public function addFileByString($content, $name, $extension)
    {

    }

    /**Add from file system
     * @return mixed
     */
    public function addFileByPath()
    {

    }

    /**
     * Send files
     * @return mixed
     */
    public function push()
    {
        $this->transport->push();
    }
}