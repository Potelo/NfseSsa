<?php

namespace Potelo\NfseSsa;


class Response
{
    /**
     * @var bool
     */
    private $status;

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}