<?php

namespace JSYF\Kernel\Services;

use JSYF\Kernel\Exceptions\FileNotFoundException;

class FileNotFoundHandlerServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['FileNotFoundHandler'] = function ($c) {
            return new FileNotFoundException();
        };
    }
}