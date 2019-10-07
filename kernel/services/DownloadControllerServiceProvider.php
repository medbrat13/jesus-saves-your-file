<?php

namespace JSYF\Kernel\Services;

use JSYF\App\Controllers\DownloadController;

class DownloadControllerServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['DownloadController'] = function ($c) {
            $controller = new DownloadController($c['FileMapper'], $c['response'], $c['FileNotExistsHandler']);

            return $controller;
        };
    }
}