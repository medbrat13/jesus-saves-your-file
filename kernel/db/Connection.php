<?php

namespace JSYF\Kernel\DB;

/**
 * Класс для соединения с бд
 */
class Connection {

    /**
     * @var DBConfig Конфиг базы данных
     */
    private $config;

    /**
     * @var \PDO Экземпляр класса \PDO
     */
    private $pdo;

    public function __construct(DBConfig $config)
    {
        $this->config = $config;
        $this->connect();
    }

    /**
     * Выполняется соединение с бд
     *
     * @return void
     */
    private function connect(): void
    {
        $this->pdo = new \PDO(
            $this->config->getDsn(), $this->config->getUser(), $this->config->getPass(), $this->config->getOpt());
    }

    /**
     * @return \PDO Объект PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}
