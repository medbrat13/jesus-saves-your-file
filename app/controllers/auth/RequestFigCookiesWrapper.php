<?php

namespace JSYF\App\Controllers\Auth;

use Dflydev\FigCookies\Cookie;
use Dflydev\FigCookies\FigRequestCookies;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Класс-обертка над FigCookies
*/
class RequestFigCookiesWrapper implements RequestCookiesInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param string $key
     * @return Cookie
     */
    public function get(ServerRequestInterface $request, string $key): Cookie
    {
        return FigRequestCookies::get($request, $key);
    }
}