<?php

namespace JSYF\Kernel\Services;

use JSYF\Kernel\Exceptions\FileNotExistsException;

class FileNotExistsHandlerServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['FileNotExistsHandler'] = function ($c) {
            return new FileNotExistsException();
        };
    }
}