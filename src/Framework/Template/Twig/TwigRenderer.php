<?php

namespace Framework\Template\Twig;

use Framework\Template\TemplateRenderer;
use Twig\Environment;

class TwigRenderer implements TemplateRenderer
{
    private $twig;
    private $extension;

    public function __construct(Environment $twig, $extension)
    {
        $this->twig = $twig;
        $this->extension = $extension;
    }

    /**
     * @param $name
     * @param array $params
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($name, array $params = []): string
    {
        return $this->twig->render($name . $this->extension, $params);
    }

    public function addGlobal(string $name, $value)
    {
        $this->twig->addGlobal($name, $value);
    }
}
