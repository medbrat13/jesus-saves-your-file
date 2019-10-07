<?php

namespace JSYF\Kernel\Services;

use Sphinx\SphinxClient;

class SphinxServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['sphinx'] = function () {
            $config = require_once ROOT . '/config/sphinx_conf.php';

            $sphinx = new SphinxClient();
            $sphinx->setServer($config['host'], $config['port']);
            $sphinx->setArrayResult(true);

            return $sphinx;
        };
    }
}