<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 17.03.2018
 * Time: 13:42
 */

declare(strict_types=1);

namespace App\Repositories\Menu;

use App\Entities\Menu\MenuItem;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class MenuRepository
 * @package App\Common\Repositories\Menu
 */
class MenuRepository
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var string
     */
    private $userName;

    /**
     * MenuRepository constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param ServerRequestInterface $request
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
        $this->userName = $request->getAttribute('admin') ? 'admin' : '';
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getItems(): array
    {
        if ($this->request->getAttribute('admin')) {
            return $this->getAdminItems();
        } else {
            return $this->getGuestItems();
        }
    }

    private function getAdminItems(): array
    {
        return [
            new MenuItem('Создать задачу', $this->router->generate('make', [])),
            new MenuItem('Выйти (' . $this->userName . ')', $this->router->generate('logout', []), 'POST')
        ];
    }

    /**
     * @return array
     */
    private function getGuestItems(): array
    {
        return [
            new MenuItem('Создать задачу', $this->router->generate('make', [])),
            new MenuItem('Войти', $this->router->generate('login', [])),

        ];
    }
}
