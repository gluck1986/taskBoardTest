<?php
/**
 * @see       https://github.com/zendframework/zend-stratigility for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-stratigility/blob/master/LICENSE.md New BSD License
 */

namespace Framework\Http\Pipeline;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewarePipeInterface extends MiddlewareInterface, RequestHandlerInterface
{
    /**
     * @param MiddlewareInterface|RequestHandlerInterface $middleware
     * @return mixed
     */
    public function pipe($middleware);
}
