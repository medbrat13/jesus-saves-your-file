<?php

namespace JSYF\App\Models\Mappers;

use JSYF\App\Models\Entities\File;
use JSYF\Kernel\Base\DataMapper;
use JSYF\Kernel\DB\Connection;
use JSYF\Kernel\Helpers\QueryBuilder;

/**
 * Преобразователь данных сущности "Файл"
 */
class FilesMapper extends DataMapper
{
    public function __construct(Connection $connection, QueryBuilder $builder)
    {
        parent::__construct($connection, $builder);

        $this->selectStmt = $this->connection->getPdo()->prepare("
            SELECT * FROM files WHERE id=?
        ");

        $this->insertStmt = $this->connection->getPdo()->prepare("
            INSERT INTO files (
                               \"name\", 
                               album,
                               size,
                               resolution, 
                               duration, 
                               comment,
                               path,
                               \"user\"
                            ) VALUES (:name, :album, :size, :resolution, :duration, :comment, :path, :user)
        ");

    }

    protected function doFind(array $values): array
    {
        $query = $this->builder->select($values);
        $selectStmt = $this->connection->getPdo()->prepare($query);
        $selectStmt->execute();

        $table = $selectStmt->fetchAll();
        $selectStmt->closeCursor();

        if (!is_array($table) || empty($table) ) {
            return [];
        }

        $objects = [];
        foreach ($table as $row) {
            array_push($objects, $this->createObject($row));
        }

        return $objects;
    }

    protected function doInsert(object $object): void
    {
        $values = [
            'name' => $object->getFileName(),
            'album' => $object->getAlbum(),
            'size' => $object->getSize(),
            'resolution' => $object->getResolution(),
            'duration' => $object->getDuration(),
            'comment' => $object->getComment(),
            'path' => $object->getPath(),
            'user' => $object->getUser(),
        ];

        var_dump($values);
        var_dump($this->insertStmt);

        $this->insertStmt->execute($values);

        $id = $this->connection->getPdo()->lastInsertId();
        $object->setId($id);
    }

    protected function doCreateObject(array $row): object
    {
        $object = new File(
            $row['name']        ?? $row[0],
            $row['album']       ?? $row[1],
            $row['size']        ?? $row[2],
            $row['resolution']  ?? $row[3],
            $row['duration']    ?? $row[4],
            $row['comment']     ?? $row[5],
            $row['path']        ?? $row[6],
            $row['user']        ?? $row[7],
            $row['id'] = null,
            $row['date'] = null
        );

        return $object;
    }
}