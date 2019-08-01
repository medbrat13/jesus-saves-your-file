<?php

namespace JSYF\App\Models\Mappers;

use JSYF\App\Models\Entities\File;
use JSYF\Kernel\Base\DataMapper;
use JSYF\Kernel\DB\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;

/**
 * Преобразователь данных сущности "Файл"
 */
class FilesMapper extends DataMapper
{
    public function __construct(Connection $connection, QueryBuilderHandler $builder)
    {
        parent::__construct($connection, $builder);
    }

    /**
     * Считает количество записей
     * @param array $values
     * @return int
     */
    protected function doCount(array $values): int
    {
        $query = $this->builder->table('files');

        if ($values['searchBy'] !== null) {
            $query->where($values['searchWhere'], '=', $values['searchBy']);
        }

        $result = $query->count();

        return (int)$result;
    }

    /**
     * Ищет записи исходя из входных данных
     * @param array $values
     * @return array
     */
    protected function doFind(array $values): array
    {
        $query = $this->builder->table('files');

        if (array_key_exists('searchBy', $values) && $values['searchBy'] !== null) {
            $query->where($values['searchWhere'], '=', $values['searchBy']);
        }

        if (array_key_exists('orderBy', $values) && $values['orderBy'] !== null) {
            $query->orderBy($values['orderBy'], $values['orderDir']);
        }

        if (array_key_exists('offset', $values) && $values['offset'] !== null) {
            $query->offset($values['offset']);
        }

        if (array_key_exists('limit', $values) && $values['limit'] !== null) {
            $query->limit($values['limit']);
        }

        $result = $query->get();

        if (!is_array($result) || empty($result) ) {
           return [];
        }

        $objects = [];
        foreach ($result as $row) {
            array_push($objects, $this->create((array)$row));
        }

        return $objects;
    }

    /**
     * Вставляет запись в базу данных
     * @param object $object
     * @return int
     */
    protected function doInsert(object $object): int
    {
        $values = [
            'name' => $object->getName(),
            'album' => $object->getAlbum(),
            'size' => $object->getSize(),
            'resolution' => $object->getResolution(),
            'duration' => $object->getDuration(),
            'comment' => $object->getComment(),
            'path' => $object->getPath(),
            'preview_path' => $object->getPreviewPath(),
            'user' => $object->getUser(),
            'ext' => $object->getExt(),
        ];

        $id = $this->builder->table('files')->insert($values);

        return $id;
    }

    /**
     * Создает объект-сущность Файл
     * @param array $row
     * @return object
     */
    protected function doCreateObject(array $row): object
    {
        $object = new File(
            $row['name'] ?? null,
            $row['album'] ?? null,
            $row['size']        ?? null,
            $row['resolution']  ?? null,
            $row['duration']    ?? null,
            $row['comment']     ?? null,
            $row['path']        ?? null,
            $row['preview_path']        ?? null,
            $row['user']        ?? null,
            $row['ext']        ?? null,
            $row['id'] ?? null,
            $row['date'] ?? null
        );

        return $object;
    }
}