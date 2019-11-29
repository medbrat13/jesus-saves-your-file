<?php

namespace JSYF\App\Controllers\Auth;

use Dflydev\FigCookies\Cookie;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;

/**
 * Класс-обертка над FigCookies
 */
class ResponseFigCookiesWrapper implements ResponseCookiesInterface
{
    /**
     * Получает значение response cookie
     * @param ResponseInterface $response
     * @param string $key
     * @return mixed
     */
    public function get(ResponseInterface $response, string $key)
    {
        return FigResponseCookies::get($response, $key);
    }

    /**
     * Устанавливает response cookie
     * @param ResponseInterface $response
     * @param string $key
     * @param string $value
     * @param int $expires
     * @return ResponseInterface
     */
    public function set(ResponseInterface $response, string $key, string $value, int $expires = 0): ResponseInterface
    {
        return FigResponseCookies::set($response, SetCookie::create($key)->withValue($value)->withExpires($expires));
    }
}