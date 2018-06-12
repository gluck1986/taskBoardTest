<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 11.06.2018
 * Time: 19:27
 */
declare(strict_types=1);

namespace App\Services\Dto;

class UploadSettings
{
    private $path;
    private $maxWidth;
    private $masHeight;

    public function __construct(string $path, $maxWidth, $maxHeight)
    {
        $this->path = $path;
        $this->masHeight = $maxHeight;
        $this->maxWidth = $maxWidth;
    }

    /**
     * @return mixed
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * @return mixed
     */
    public function getMasHeight()
    {
        return $this->masHeight;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
