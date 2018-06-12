<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 17.03.2018
 * Time: 13:39
 */

namespace App\Http\Template\Twig\Extensions\MenuItems;

use App\Entities\Menu\MenuItem;
use App\Repositories\Menu\MenuRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class MenuItemsExtension
 * @package App\Http\Template\Twig\Extensions\MenuItems
 */
class MenuItemsExtension extends AbstractExtension
{
    /**
     * @var MenuRepository
     */
    private $menuRepository;
    /**
     * @var \Twig_Environment
     */
    private $renderer;

    /**
     * MenuItemsExtension constructor.
     * @param MenuRepository $menuRepository
     */
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('navBarWidget', [$this, 'buildMenu'], ['needs_environment' => true]),
        ];
    }

    /**
     * @param \Twig_Environment $environment
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function buildMenu(\Twig_Environment $environment): string
    {
        $this->renderer = $environment;
        $menuSource = $this->menuRepository->getItems();
        $itemsHtml = $this->buildItems($menuSource);

        return $this->renderer->render('widget/navBar/navBar.html.twig', ['menuItemsHtml' => $itemsHtml]);
    }

    /**
     * @param MenuItem[] $items
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function buildItems(array $items): string
    {
        $resultHtml = '';
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $resultHtml .= PHP_EOL . $this->buildSubMenu($item->name, $item->children);
            } else {
                $resultHtml .= PHP_EOL . $this->buildItem($item);
            }
        }
        return $resultHtml;
    }

    /**
     * @param string $label
     * @param array $items
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function buildSubMenu(string $label, array $items): string
    {
        $children = $this->buildItems($items);

        return $this->renderer->render(
            'widget/navBar/subMenuContainer.html.twig',
            ['label' => $label, 'children' => $children]
        );
    }

    /**
     * @param MenuItem $item
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function buildItem(MenuItem $item): string
    {
        $href = $item->url;
        $label = $item->name;
        if ($item->method === 'POST') {
            $resultHtml = $this->renderer->render(
                'widget/navBar/menuPostItem.html.twig',
                ['label' => $label, 'href' => $href]
            );
        } else {
            $resultHtml = $this->renderer->render(
                'widget/navBar/menuItem.html.twig',
                ['label' => $label, 'href' => $href]
            );
        }
        return $resultHtml;
    }
}
