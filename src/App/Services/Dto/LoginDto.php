<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 10.06.2018
 * Time: 15:35
 */
declare(strict_types=1);

namespace App\Services\Dto;

class LoginDto
{
    public $name;
    public $password;
    public $errors = [];

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
