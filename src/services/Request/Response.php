<?php

namespace Potelo\NfseSsa\Request;


class Response
{
    /**
     * @var bool
     */
    private $status;

    /**
     * @var array
     */
    private $errors = [];

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

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }
}