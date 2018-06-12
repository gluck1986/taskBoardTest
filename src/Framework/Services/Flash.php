<?php
/**
 * Created by PhpStorm.
 * User: gluck
 * Date: 10.05.2018
 * Time: 21:44
 */
declare(strict_types=1);

namespace Framework\Services;

use Aura\Session\Segment;
use Aura\Session\Session;
use Psr\Http\Message\ServerRequestInterface;

class Flash
{
    public function info(ServerRequestInterface $request, string $message)
    {
        /** @var Session $session */
        $session = $request->getAttribute(Session::class);

        /** @var Segment $segment */
        $segment = $session->getSegment('app');
        $info = $segment->getFlash('info', []);
        $info[] = $message;
        $segment->setFlash('info', $info);
    }

    public function error(ServerRequestInterface $request, string $message)
    {
        /** @var Session $session */
        $session = $request->getAttribute(Session::class);

        /** @var Segment $segment */
        $segment = $session->getSegment('app');
        $info = $segment->getFlash('err', []);
        $info[] = $message;
        $segment->setFlash('err', $info);
    }
}
