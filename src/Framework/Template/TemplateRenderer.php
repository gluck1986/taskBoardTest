<?php

namespace Framework\Template;

interface TemplateRenderer
{
    public function render($name, array $params = []): string;

    public function addGlobal(string $name, $value);
}
