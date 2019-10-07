<?php

namespace JSYF\Kernel\Services;

use JSYF\App\Controllers\UserController;

class UserControllerServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['UserController'] = function ($c) {
            $controller = new UserController();

            return $controller;
        };
    }
}