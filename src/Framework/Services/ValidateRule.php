<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 06.04.2018
 * Time: 9:19
 */
declare(strict_types=1);

namespace Framework\Services;

class ValidateRule
{
    public $field;
    public $rule;
    public $closure;
    public $message;

    /**
     * ValidateRule constructor.
     * @param $field
     * @param $rule
     * @param $closure
     * @param $message
     */
    public function __construct($field, $rule, $message, $closure = null)
    {
        $this->field = $field;
        $this->rule = $rule;
        $this->closure = $closure;
        $this->message = $message;
    }
}
