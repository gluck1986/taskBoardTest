<?php

use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$aggregatorSettings = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/settings/{{,*.}global,{,*.}local}.php'),
]);

$aggregatorAbstractFactories = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/abstract_factories/{{,*.}global,{,*.}local}.php'),
]);

$aggregatorFactories = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/{factories,middleware_factories}/{{,*.}global,{,*.}local}.php'),
]);

return [
    'services' => [
        'config' => $aggregatorSettings->getMergedConfig()
    ],
    'factories' => $aggregatorFactories->getMergedConfig(),
    'abstract_factories' => $aggregatorAbstractFactories->getMergedConfig()
];
