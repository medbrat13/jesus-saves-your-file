<?php

namespace JSYF\App\Models\Mappers;

use ErrorException;
use Foolz\SphinxQL\Exception\ConnectionException;
use Foolz\SphinxQL\Exception\DatabaseException;
use Foolz\SphinxQL\Exception\SphinxQLException;
use Foolz\SphinxQL\SphinxQL;
use JSYF\App\Models\Entities\File;
use JSYF\Kernel\Base\DataMapper;
use JSYF\Kernel\DB\Connection;
use JSYF\Kernel\Exceptions\FileNotFoundException;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Sphinx\SphinxClient;

/**
 * Преобразователь данных сущности "Файл"
 */
class FileMapper extends DataMapper
{
    /**
     * @var FileNotFoundException Исключение не найденного файла
     */
    private $fileNotFoundException;

    /**
     * @var SphinxQL Query Builder sphinxQL
     */
    private $spinxQL;

    public function __construct(Connection $connection, QueryBuilderHandler $builder,
                                SphinxClient $sphinx, SphinxQL $sphinxQL, FileNotFoundException $fileNotFoundException)
    {
        parent::__construct($connection, $builder, $sphinx);

        $this->fileNotFoundException = $fileNotFoundException;
        $this->spinxQL = $sphinxQL;
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
        $subQuery = $this->builder->table('users');

        try {
            if (array_key_exists('searchQuery', $values) && $values['searchQuery'] !== null) {
                $idList = [];

                $realTimeList = $this->spinxQL->select('id')->from('rt')
                    ->match('name', $values['searchQuery'])->execute()->fetchAllAssoc();

                if (is_array($realTimeList) && !empty($realTimeList)) {
                    for ($i = 0; $i < count($realTimeList); $i++) {
                        array_push($idList, $realTimeList[$i]['id']);
                    }
                }

                $queryResult = $this->sphinx->query($values['searchQuery']);

                if (is_bool($queryResult)) {
                    throw new \Exception('Не работает поисковой сервер');
                } else if (!array_key_exists('matches', $queryResult)) {
                    throw $this->fileNotFoundException;
                } else {
                    $matches = $queryResult['matches'];
                }

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

        if ($values['searchBy'] !== null && $values['searchWhere'] === 'user') {
            $user = $subQuery->where('name', '=', $values['searchBy'])->first();
            if (!is_object($user)) goto filenotfound;

            $query->where($values['searchWhere'], '=', $user->id);
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
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws ErrorException
     * @throws SphinxQLException
     */
    protected function doFind(array $values): array
    {
        # объекты-файлы
        $objects = [];

        # запрос
        $query = $this->builder->table('files');
        $subQuery = $this->builder->table('users');

        # find one
        if ($values['searchWhere'] === 'id') {
            $file = $query->where('id', '=', $values['searchBy'])->first();
            $user = $subQuery->where('id', '=', $file->user)->first();

            if (!is_object($user)) goto filenotfound;
            $file->user = $user->name;
            array_push($objects, $this->doCreateObject((array)$file));
            return $objects;
        }

        if ($values['searchWhere'] === 'path') {
            $file = $query->where('path', '=', $values['searchBy'])->first();
            $user = $subQuery->where('id', '=', $file->user)->first();
            $file->user = $user->name;
            array_push($objects, $this->doCreateObject((array)$file));
            return $objects;
        }

        try {
            # если есть поисковой запрос
            if (array_key_exists('searchQuery', $values) && $values['searchQuery'] !== null) {
                $idList = [];

                $realTimeList = $this->spinxQL->select('id')->from('rt')
                    ->match('name', $values['searchQuery'])->execute()->fetchAllAssoc();

                if (is_array($realTimeList) && !empty($realTimeList)) {
                    for ($i = 0; $i < count($realTimeList); $i++) {
                        array_push($idList, $realTimeList[$i]['id']);
                    }
                }

                $queryResult = $this->sphinx->query($values['searchQuery']);

                if (!is_bool($queryResult) && array_key_exists('matches', $queryResult)) {
                    $matches = $queryResult['matches'];

                    for ($i = 0; $i < count($matches); $i++) {
                        array_push($idList, $matches[$i]['id']);
                    }
                } else {
                    throw $this->fileNotFoundException;
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

        if (array_key_exists('select', $values) && $values['select'] !== null) {
            $query->select($values['select']);
        }


        # если есть по чему искать
        if (array_key_exists('searchBy', $values) && $values['searchBy'] !== null) {
            $subQuery = $subQuery->where('name', '=', $values['searchBy']);
            $user = $subQuery->first();
            $filesResult = $query->where('user', '=', $user->id)->get();
            if (is_object($user) && $user->id !== null) {
                if (!is_array($filesResult) || empty($filesResult)) {
                    return [];
                }

                foreach ($filesResult as $file) {
                    $file->user = $user->name;
                    array_push($objects, $this->doCreateObject((array)$file));
                }
            } else {
                goto filenotfound;
            }
        } else {
            $filesResult = $query->get();
            $usersResult = $subQuery->get();

            if (!is_array($filesResult) || empty($filesResult)) {
                return [];
            }

            foreach ($filesResult as $file) {
                foreach ($usersResult as $user) {
                    if ($file->user === $user->id) {
                        $file->user = $user->name;
                        array_push($objects, $this->doCreateObject((array)$file));
                    }
                }
            }
        }

        filenotfound:

        return $objects;
    }

    /**
     * Вставляет запись в базу данных
     * @param object $object
     * @return int
     * @throws ConnectionException
     * @throws DatabaseException
     * @throws SphinxQLException
     */
    protected function doInsert(object $object): int
    {
        $values = [
            'name' => $object->getName(),
            'size' => $object->getSize(),
            'resolution' => $object->getResolution(),
            'duration' => $object->getDuration(),
            'path' => $object->getPath(),
            'preview_path' => $object->getPreviewPath(),
            'ext' => $object->getExt(),
            'user' => $object->getUser()
        ];

        $id = $this->builder->table('files')->insert($values);
        $this->spinxQL->insert()->into('rt')->columns(['id', 'name'])->values($id, $object->getName())->execute();

        return $id;
    }

    /**
     * Создает объект-сущность Файл
     * @param array $row
     * @return File
     */
    protected function doCreateObject(array $row): File
    {
        $object = new File(
            $row['id'] ?? null,
            $row['name'] ?? null,
            $row['date']        ?? null,
            $row['size']        ?? null,
            $row['resolution']  ?? null,
            $row['duration']    ?? null,
            $row['path']        ?? null,
            $row['preview_path']        ?? null,
            $row['ext']        ?? null,
            $row['user']  ?? null
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