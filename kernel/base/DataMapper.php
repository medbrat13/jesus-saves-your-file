<?php

namespace JSYF\Kernel\Base;

use JSYF\Kernel\DB\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;

/**
 * Базовый преобразователь данных
 */
abstract class DataMapper
{
    protected $connection;
    protected $builder;

    public function __construct(Connection $connection, QueryBuilderHandler $builder)
    {
        $this->connection = $connection;
        $this->builder = $builder;
    }

    /**
     * Считает количество строк (обертка для doCount())     *
     * @param string|null $searchBy
     * @param string $searchWhere
     * @return int
     */
    public function count(string $searchBy = null, string $searchWhere = null): int
    {
        $values = ['searchBy' => $searchBy, 'searchWhere' => $searchWhere];

        return $this->doCount($values);
    }

    /**
     * Создает объект-сущность (обертка для doCreateObject())
     * @param array $values
     * @return object
     */
    public function create(array $values): object
    {
        return $this->doCreateObject($values);
    }

    /**
     * Ищет одну строку (обертка для doFind())
     * @param string $searchBy
     * @param string $searchWhere
     * @return object
     */
    public function findOne(string $searchBy, string $searchWhere = 'id'): object
    {
        $values = [
            'searchBy'    => $searchBy,
            'searchWhere' => $searchWhere
        ];

        return $this->doFind($values)[0];
    }

    /**
     * Ищет все вхождения по заданным параметрам (обертка для findAll())
     * @param string|null $searchBy
     * @param string $searchWhere
     * @param string|null $orderBy
     * @param string|null $orderDir
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findAll(string $searchBy = null, string $searchWhere = null, string $orderBy = null, string $orderDir = null, int $limit = null, $offset = null): array
    {
        $values = [
            'searchBy'    => $searchBy,
            'searchWhere' => $searchWhere,
            'orderBy'     => $orderBy,
            'orderDir'    => $orderDir,
            'limit'       => $limit,
            'offset'      => $offset
        ];

        return $this->doFind($values);
    }

    /**
     * Вставляет информацию о сущности в таблицу (обертка для doInsert())
     * @param object $object
     * @return int
     */
    public function insert(object $object): int
    {
        return $this->doInsert($object);
    }

    abstract protected function doCount(array $values);
    abstract protected function doFind(array $values);
    abstract protected function doInsert(object $object);
    abstract protected function doCreateObject(array $row);
}