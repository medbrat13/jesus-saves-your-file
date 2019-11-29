<?php

namespace JSYF\App\Controllers\Auth;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Класс, отвечающий за авторизацию
 */
class AuthController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var RequestCookiesInterface
     */
    private $requestCookiesHandler;

    /**
     * @var $responseCookiesInterface
     */
    private $responseCookiesHandler;

    public function __construct(Request $request, Response $response, RequestCookiesInterface $requestCookiesHandler, ResponseCookiesInterface $responseCookiesHandler)
    {
        $this->request = $request;
        $this->response = $response;
        $this->requestCookiesHandler = $requestCookiesHandler;
        $this->responseCookiesHandler = $responseCookiesHandler;
    }

    /**
     * Регистрирует пользователя
     */
    public function signUp(): Response
    {
        return $this->responseCookiesHandler->set($this->response, 'user', uniqid('id'),  time()+60*60*24*365);
    }

    /**
     * Проверяет, авторизован ли пользователь через cookie
     * @return bool
     */
    public function isUserAuthorized(): bool
    {
        if ($this->requestCookiesHandler->get($this->request, 'user')->getValue() === null) {
            return false;
        }

        return true;
    }
}