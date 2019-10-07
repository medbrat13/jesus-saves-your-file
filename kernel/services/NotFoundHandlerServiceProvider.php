<?php

namespace JSYF\Kernel\Services;

class NotFoundHandlerServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['notFoundHandler'] = function ($c) {
            return function ($request, $response) {
                return $response->withHeader('X-Accel-Redirect', "/errors/page-404.html")
                    ->withHeader('Content-Disposition', 'inline')
                    ->withHeader('Content-Type', 'text/html')
                    ->withStatus(404);
            };
        };
    }
}