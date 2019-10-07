<?php

namespace JSYF\Kernel\Services;

use JSYF\Kernel\DB\Connection as MyConnection;

class ConnectionServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['connection'] = function ($c) {
            $connection = new MyConnection($c['db_config']);

            return $connection;
        };
    }
}