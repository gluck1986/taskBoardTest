<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 17.03.2018
 * Time: 13:52
 */

namespace App\Entities\Menu;

class MenuItem
{
    public $name;
    public $url;
    public $method = 'GET';
    public $children;

    /**
     * MenuItem constructor.
     * @param string $name
     * @param string $url
     * @param string $method
     * @param array $children
     */
    public function __construct(string $name, string $url, $method = 'GET', array $children = [])
    {
        $this->name = $name;
        $this->url = $url;
        $this->method = $method;
        $this->children = $children;
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }
}
