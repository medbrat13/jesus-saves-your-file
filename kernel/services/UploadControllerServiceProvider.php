<?php

namespace JSYF\Kernel\Services;

use JSYF\App\Controllers\UploadController;

class UploadControllerServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['UploadController'] = function ($c) {
            $controller = new UploadController($c['getid3'], $c['FileMapper'], $c['UserMapper']);

            return $controller;
        };
    }
}