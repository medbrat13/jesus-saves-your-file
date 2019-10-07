<?php

namespace JSYF\Kernel\Services;

class FilesControllerServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['FilesController'] = function ($c) {
            $controller = new \JSYF\App\Controllers\FilesController($c['FileMapper']);

            return $controller;
        };
    }
}