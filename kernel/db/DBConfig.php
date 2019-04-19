<?php

namespace JSYF\Kernel\DB;

/**
 * Конфигурация базы данных
 */
class DBConfig
{
    private $driver;
    private $host;
    private $dbName;
    private $dsn;
    private $user;
    private $pass;
    private $opt;

    public function __construct(array $config)
    {
        $this->driver = $config['driver'];
        $this->host = $config['host'];
        $this->dbName = $config['dbname'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->opt = $config['opt'];
        $this->makeDsn($this->driver, $this->host, $this->dbName);
    }

    /**
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @return string
     */
    public function getOpt()
    {
        return $this->opt;
    }

    /**
     * Конструктор DSN
     *
     * @param $driver
     * @param $host
     * @param $dbName
     * @return void
     */
    private function makeDsn($driver, $host, $dbName): void
    {
        $this->dsn = "$driver:host=$host;dbname=$dbName";
    }
}