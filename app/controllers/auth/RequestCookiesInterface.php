<?php

namespace JSYF\App\Controllers\Auth;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Интерфейс для request cookies
 */
interface RequestCookiesInterface
{
    /**
     * Получает значение request cookie
     * @param ServerRequestInterface $request
     * @param string $key
     * @return mixed
     */
    public function get(ServerRequestInterface $request, string $key);
}