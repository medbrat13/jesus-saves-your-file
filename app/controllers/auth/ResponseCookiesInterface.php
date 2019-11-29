<?php

namespace JSYF\App\Controllers\Auth;

use Psr\Http\Message\ResponseInterface;

/**
 * Интерфейс для response cookies
 */
interface ResponseCookiesInterface
{
    /**
     * Получает значение response cookie
     * @param ResponseInterface $response
     * @param string $key
     * @return mixed
     */
    public function get(ResponseInterface $response, string $key);

    /**
     * Устанавливает response cookie
     * @param ResponseInterface $response
     * @param string $key
     * @param string $value
     * @param int $expires
     * @return mixed
     */
    public function set(ResponseInterface $response, string $key, string $value, int $expires): ResponseInterface;
}