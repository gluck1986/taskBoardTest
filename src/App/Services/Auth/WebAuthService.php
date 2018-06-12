<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 22.03.2018
 * Time: 8:05
 */
declare(strict_types=1);

namespace App\Services\Auth;

use App\Services\Dto\LoginDto;
use Framework\Services\FormLoader;
use Framework\Services\ValidateRule;
use Framework\Services\Validator;
use Psr\Http\Message\ServerRequestInterface;

class WebAuthService
{

    private $authenticator;
    private $loader;
    private $validator;

    public function __construct(Authenticate $authenticate, FormLoader $loader, Validator $validator)
    {
        $this->authenticator = $authenticate;
        $this->loader = $loader;
        $this->validator = $validator;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     * @throws \ReflectionException
     */
    public function authenticate(ServerRequestInterface $request): ServerRequestInterface
    {
        /** @var LoginDto $loginDto */
        $loginDto = $this->loader->load($request->getParsedBody(), LoginDto::class);
        $request = $request->withAttribute(LoginDto::class, $loginDto);
        if (count($loginDto->errors = $this->validate($loginDto)) > 0) {
            return $request;
        }
        $request = $this->doProcessAuthenticate($request, $loginDto);
        $request->getAttribute('admin', false)
        || $this->addErr($loginDto, 'Неверное имя пользователя или пароль');

        return $request;
    }

    private function validate(LoginDto $loginDto): array
    {
        return $this->validator->validate($loginDto, [
            new ValidateRule('name', 'string:1..32', 'Необходимо заполнить имя пользователя'),
            new ValidateRule('password', 'string:1..32', 'Необходимо заполнить пароль'),
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param LoginDto $loginDto
     * @return ServerRequestInterface
     */
    private function doProcessAuthenticate(
        ServerRequestInterface $request,
        LoginDto $loginDto
    ): ServerRequestInterface {
        $isSigIn = $this->authenticator->auth($loginDto->name, $loginDto->password);

        if ($isSigIn && $this->authenticator->setSessionAuth($request)) {
            $request = $request->withAttribute('admin', true);
        }

        return $request;
    }

    private function addErr(LoginDto $loginDto, string $err)
    {
        $loginDto->errors[] = $err;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function logout(ServerRequestInterface $request): ServerRequestInterface
    {
        return $this->authenticator->logOut($request);
    }
}
