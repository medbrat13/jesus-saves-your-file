<?php

namespace JSYF\Kernel\Services;

use getID3;

class Getid3ServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['getid3'] = function ($c) {
            $getid3 = new getID3();

            return $getid3;
        };
    }
}