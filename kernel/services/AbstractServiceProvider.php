<?php

namespace JSYF\Kernel\Services;

use Slim\Container;

/**
 * Абстрактный поставщик сервисов
 */
abstract class AbstractServiceProvider
{
    /**
     * @var Container Экземпляр класса DI
     */
    protected $di;

    public function __construct(Container $di)
    {
        $this->di = $di;
    }
    /**
     * Инициализирует новый сервис
     *
     * @return mixed
     * @throws Exception
     */
    abstract function init();
}