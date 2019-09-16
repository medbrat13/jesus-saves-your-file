<?php

namespace JSYF\App\Models\Mappers;

use JSYF\App\Models\Entities\Album;
use JSYF\Kernel\Base\DataMapper;
use JSYF\Kernel\DB\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Sphinx\SphinxClient;

/**
 * Преобразователь данных сущности "Альбом"
 */
class AlbumMapper extends DataMapper
{

    public function __construct(Connection $connection, QueryBuilderHandler $builder, SphinxClient $sphinx)
    {
        parent::__construct($connection, $builder, $sphinx);
    }

    protected function doCount(array $values): int
    {
        return 0;
    }

    protected function doFind(array $values): array
    {
        $objects = [];
        $query = $this->builder->table('albums');

        if (array_key_exists('searchBy', $values) && $values['searchBy'] !== null) {

            if ($values['searchWhere'] === 'user') {
                $subQuery = $this->builder->table('users')->where('name', '=', $values['searchBy'])->select('id');

                $userId = $subQuery->get();

                $query->where($values['searchWhere'], '=', $userId);
            } else {
                $query->where($values['searchWhere'], '=', $values['searchBy']);
            }
        }

        if (array_key_exists('orderBy', $values) && $values['orderBy'] !== null) {
            $query->orderBy($values['orderBy'], $values['orderDir']);
        }

        $result = $query->get();

        if (is_null($result) || empty($result)) {
            array_push($objects, $this->doCreateObject([]));
            return $objects;
        }

        foreach ($result as $row) {
            array_push($objects, $this->doCreateObject((array)$row));
        }

        return $objects;
    }

    protected function doInsert(object $object)
    {
        $values = [
            'name' => $object->getName(),
            'user' => $object->getUser()
        ];

        $id = $this->builder->table('albums')->insert($выvalues);

        return $id;
    }

    protected function doCreateObject(array $row): Album
    {
        $object = new Album(
            $row['id'] ?? null,
            $row['name'] ?? null,
            $row['user'] ?? null
        );

        return $object;
    }

    protected function doDelete(array $values)
    {
        // TODO: Implement doDelete() method.
    }
}