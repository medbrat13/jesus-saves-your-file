<?php

namespace JSYF\Kernel\Base;

use JSYF\Kernel\DB\Connection;

/**
 * Базовый контроллер
 */
abstract class Controller
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var array Данные
     */
    protected $data = [];


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Устанавливает данные для передачи. Можно использовать многократно для установки различных данных.
     *
     * @param array $data Данные
     * @return void
     */
    protected function setData(array $data): void
    {
        $this->data = $this->addData($this->data, $data);
    }

    /**
     * Добавляет массив данных в массив-контейнер
     *
     * @param array $container
     * @param array $data
     * @return array
     */
    protected function addData(array $container, array $data): array
    {
        return array_merge($container, $data);
    }
}