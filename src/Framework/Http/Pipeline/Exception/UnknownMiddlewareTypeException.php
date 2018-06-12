<?php

namespace Framework\Http\Pipeline\Exception;

class UnknownMiddlewareTypeException extends \InvalidArgumentException implements ExceptionInterface
{
    private $type;

    public function __construct($type)
    {
        parent::__construct('Unknown middleware type');
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
