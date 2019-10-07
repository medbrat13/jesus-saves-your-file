<?php


namespace JSYF\Kernel\Services;

use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Foolz\SphinxQL\SphinxQL;

class SphinxqlQueryBuilderServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['sphinxql_query_builder'] = function () {
            $config = require ROOT . '/config/sphinxQL_conf.php';

            $connection = new Connection();
            $connection->setParams(['host' => $config['host'], 'port' => $config['port']]);

            $builder = new SphinxQL($connection);

            return $builder;
        };
    }
}