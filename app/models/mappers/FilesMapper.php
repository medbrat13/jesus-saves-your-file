<?php

namespace JSYF\App\Models\Mappers;

use ErrorException;
use JSYF\App\Models\Entities\File;
use JSYF\Kernel\Base\DataMapper;
use JSYF\Kernel\DB\Connection;
use JSYF\Kernel\Exceptions\FileNotFoundException;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Sphinx\SphinxClient;

/**
 * Преобразователь данных сущности "Файл"
 */
class FilesMapper extends DataMapper
{
    private $fileNotFoundException;

    public function __construct(Connection $connection, QueryBuilderHandler $builder,
                                SphinxClient $sphinx, FileNotFoundException $fileNotFoundException)
    {
        parent::__construct($connection, $builder, $sphinx);

        $this->fileNotFoundException = $fileNotFoundException;
    }

    /**
     * Считает количество записей
     * @param array $values
     * @return int
     */
    protected function doCount(array $values): int
    {
        $result = 0;
        $query = $this->builder->table('files');

        try {
            if (array_key_exists('queryString', $values)) {
                $queryResult = $this->sphinx->query($values['queryString']);

                if (is_bool($queryResult)) {
                    throw new \Exception('Не работает поисковой сервер');
                } else if (!array_key_exists('matches', $queryResult)) {
                    throw $this->fileNotFoundException;
                } else {
                    $matches = $queryResult['matches'];
                }

                $idList = [];

                for ($i = 0; $i < count($matches); $i++) {
                    array_push($idList, $matches[$i]['id']);
                }

                $query->whereIn('id', $idList);
            }
        } catch (FileNotFoundException $exception) {
            goto filenotfound;
        } catch (\Exception $exception) {
            goto searchenginenotactive;
        }


        if ($values['searchBy'] !== null) {
            $query->where($values['searchWhere'], '=', $values['searchBy']);
        }

        $result = $query->count();

        filenotfound:
        searchenginenotactive:

        return (int)$result;
    }

    /**
     * Ищет записи исходя из входных данных
     * @param array $values
     * @return array
     * @throws ErrorException
     */
    protected function doFind(array $values): array
    {
        $result = [];
        $objects = [];
        $query = $this->builder->table('files');

        if (array_key_exists('searchBy', $values) && $values['searchBy'] !== null) {
            $query->where($values['searchWhere'], '=', $values['searchBy']);
        }

        try {
            if (array_key_exists('queryString', $values)) {
                $queryResult = $this->sphinx->query($values['queryString']);

                if (!is_bool($queryResult) && array_key_exists('matches', $queryResult)) {
                    $matches = $queryResult['matches'];
                } else {
                    throw $this->fileNotFoundException;
                }

                $idList = [];

                for ($i = 0; $i < count($matches); $i++) {
                    array_push($idList, $matches[$i]['id']);
                }

                $query->whereIn('id', $idList);
            }
        } catch (FileNotFoundException $exception) {
            goto filenotfound;
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

        foreach ($result as $row) {
            array_push($objects, $this->create((array)$row));
        }

        filenotfound:

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

    /**
     * Удаляет файл/список файлов
     * @param array $values
     * @return void
     */
    protected function doDelete(array $values): void
    {
        $this->builder->table('files')->whereIn('id', $values)->delete();
    }
}