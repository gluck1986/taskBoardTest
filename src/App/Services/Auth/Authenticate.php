<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 19.03.2018
 * Time: 8:23
 */

namespace App\Services\Auth;

use Aura\Session\Session;
use Psr\Http\Message\ServerRequestInterface;

class Authenticate
{
    const SESSION_AUTH_SEGMENT = 'auth';
    const SESSION_KEY = 'userId';
    const COOKIE_TOKEN = 'token';

    private $userName;
    private $password;

    /**
     * Authenticate constructor.
     * @param string $userName
     * @param string $password
     */
    public function __construct(
        string $userName,
        string $password
    ) {
        $this->userName = $userName;
        $this->password = $password;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function authBySession(ServerRequestInterface $request)
    {
        $session = $this->getSession($request);
        $user = null;

        /**@var $session Session */
        $authSegment = $session->getSegment(self::SESSION_AUTH_SEGMENT);
        if ($authSegment->get(self::SESSION_KEY)) {
            return true;
        }

        return false;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Session
     */
    private function getSession(ServerRequestInterface $request): Session
    {
        $session = $request->getAttribute(Session::class);
        /**@var $session Session */
        return $session;
    }

    /**
     * @param string $userName
     * @param string $password
     * @return bool
     */
    public function auth(string $userName, string $password): bool
    {
        return $userName === $this->userName && $password === $this->password;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function setSessionAuth(ServerRequestInterface $request)
    {
        $session = $this->getSession($request);

        $authSegment = $session->getSegment(self::SESSION_AUTH_SEGMENT);
        $authSegment->set(self::SESSION_KEY, true);

        return true;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function logOut(ServerRequestInterface $request): ServerRequestInterface
    {
        $session = $this->getSession($request);
        $session->clear();
        $session->destroy();
        return $request;
    }
}
