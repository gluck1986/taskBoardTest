<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 16.03.2018
 * Time: 8:48
 */

namespace Framework\Http\Pipeline\Exception;

class UnknownRequestHandler extends \InvalidArgumentException implements ExceptionInterface
{
    private $type;

    public function __construct($type)
    {
        parent::__construct('Unknown RequestHandler type');
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
