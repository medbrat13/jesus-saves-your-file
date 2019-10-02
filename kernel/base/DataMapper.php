<?php

namespace JSYF\Kernel\Base;

use JSYF\Kernel\DB\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Sphinx\SphinxClient;

/**
 * Базовый преобразователь данных
 */
abstract class DataMapper
{
    protected $connection;
    protected $builder;
    protected $sphinx;

    public function __construct(Connection $connection, QueryBuilderHandler $builder, SphinxClient $sphinx)
    {
        $this->connection = $connection;
        $this->builder = $builder;
        $this->sphinx = $sphinx;
    }

    /**
     * Считает количество строк (обертка для doCount())
     * @param string|null $searchBy
     * @param string|null $searchWhere
     * @param string|null $searchQuery
     * @return int
     */
    public function count(string $searchBy = null, string $searchWhere = null, string $searchQuery = null): int
    {
        $values = ['searchBy' => $searchBy, 'searchWhere' => $searchWhere, 'searchQuery' => $searchQuery];

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
     * Ищет все вхождения по заданным параметрам (обертка для doFind())
     * @param string|null $searchBy
     * @param string $searchWhere
     * @param string|null $orderBy
     * @param string|null $orderDir
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $searchQuery
     * @param string|null $select
     * @return array
     */
    public function find(string $searchBy = null, string $searchWhere = 'id', string $orderBy = null,
                            string $orderDir = null, int $limit = null, $offset = null, string $searchQuery = null,
                         string $select = null): array
    {
        $values = [
            'searchBy'    => $searchBy,
            'searchWhere' => $searchWhere,
            'orderBy'     => $orderBy,
            'orderDir'    => $orderDir,
            'limit'       => $limit,
            'offset'      => $offset,
            'searchQuery' => $searchQuery,
            'select'      => $select
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

    /**
     * Удаляет сущность/список сущностей из таблицы (обертка для doDelete())
     * @param int $id
     */
    public function delete(int $id): void
    {
        $values = [$id];

        $this->doDelete($values);
    }

    abstract protected function doCount(array $values);
    abstract protected function doFind(array $values);
    abstract protected function doInsert(object $object);
    abstract protected function doCreateObject(array $row);
    abstract protected function doDelete(array $values);
}