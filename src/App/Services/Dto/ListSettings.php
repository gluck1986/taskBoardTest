<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 12.06.2018
 * Time: 14:22
 */
declare(strict_types=1);

namespace App\Services\Dto;

class ListSettings
{
    private $maxItems;

    /**
     * ListSettings constructor.
     * @param $maxItems int
     */
    public function __construct(int $maxItems)
    {
        $this->maxItems = $maxItems;
    }

    /**
     * @return int
     */
    public function getMaxItems(): int
    {
        return $this->maxItems;
    }
}
