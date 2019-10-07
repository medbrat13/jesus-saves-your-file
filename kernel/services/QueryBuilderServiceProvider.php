<?php

namespace JSYF\Kernel\Services;

use Pixie\Connection as PixieConnection;
use Pixie\QueryBuilder\QueryBuilderHandler;

class QueryBuilderServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['query_builder'] = function () {
            $config = require ROOT . '/config/db_conf.php';

            $connection = new PixieConnection('pgsql', [
                'driver'   => $config['driver'],
                'host'     => $config['host'],
                'database' => $config['dbname'],
                'username' => $config['user'],
                'password' => $config['pass']
            ]);

            $builder = new QueryBuilderHandler($connection);

            return $builder;
        };
    }
}