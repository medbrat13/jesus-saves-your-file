<?php

namespace JSYF\App\Models\Mappers;

use JSYF\App\Models\Entities\File;
use JSYF\App\Models\Entities\User;
use JSYF\Kernel\Base\DataMapper;
use JSYF\Kernel\DB\Connection;
use JSYF\Kernel\Exceptions\FileNotFoundException;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Sphinx\SphinxClient;

/**
 * Преобразователь данных сущности "Пользователь"
 */
class UserMapper extends DataMapper
{
    public function __construct(Connection $connection, QueryBuilderHandler $builder, SphinxClient $sphinx)
    {
        parent::__construct($connection, $builder, $sphinx);
    }

    protected function doCount(array $values)
    {
        // TODO: Implement doCount() method.
    }

    protected function doFind(array $values): array
    {
        $objects = [];

        $query = $this->builder->table('users');

        if ($values['searchBy'] !== null) {
            $query = $query->where($values['searchWhere'], '=', $values['searchBy']);
        }

        $result = $query->get();

        if (!is_array($result) || empty($result)) {
            return [$this->doCreateObject([])];
        }

        foreach ($result as $item) {
            array_push($objects, $this->doCreateObject((array)$item));
        }

        return $objects;
    }

    protected function doInsert(object $object): int
    {
        $values = [
            'name' => $object->getName()
        ];

        $id = $this->builder->table('users')->insert($values);

        return $id;
    }

    protected function doCreateObject(array $row): User
    {
        $object = new User(
            $row['id'] ?? null,
            $row['name'] ?? null
        );

        return $object;
    }

    protected function doDelete(array $values)
    {
        // TODO: Implement doDelete() method.
    }

}