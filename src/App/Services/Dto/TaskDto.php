<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 11.06.2018
 * Time: 17:23
 */
declare(strict_types=1);

namespace App\Services\Dto;

class TaskDto
{
    public $id;

    public $userName;

    public $email;

    public $description;

    public $resolved;

    public $image;



    public $errors = [];

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
