<?php

namespace JSYF\Kernel\Services;

use JSYF\Kernel\DB\DBConfig;

class DBConfigServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['db_config'] = function () {
            $config = require ROOT . '/config/db_conf.php';
            $db_config = new DBConfig($config);

            return $db_config;
        };
    }
}