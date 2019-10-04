<?php

namespace JSYF\App\Controllers;

use JSYF\App\Models\Mappers\FileMapper;

/**
 * Контроллер для работы с файлами
 */
class FilesController
{
    /**
     * @var FileMapper
     */
    private $fileMapper;

    /**
     * @var string Фалойвая директория
     */
    private $filesDir = ROOT . '/files';

    public function __construct(FileMapper $fileMapper)
    {
        $this->fileMapper = $fileMapper;
    }

    /**
     * Ищет список файлов на основе входных параметров и возвращает его
     * @param string|null $userId
     * @param string|null $sortBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function indexAction(string $userId = null, string $sortBy = null,int $limit = null, int $offset = null): array
    {
        $filesList = $this->fileMapper->find(
            $userId,
            'user',
            $sortBy !== null && $sortBy !== '' ? $this->getOrderParams($sortBy)['orderBy'] : 'date',
            $sortBy !== null && $sortBy !== '' ? $this->getOrderParams($sortBy)['orderDir'] : 'DESC',
            $limit,
            $offset
        );

        foreach ($filesList as $file) {
            $this->prepareFile($file);
        }

        $filesTotalQuantity = $this->fileMapper->count($userId, 'user');

        $anyFilesLeft = ($filesTotalQuantity > ($offset + $limit)) ? true : false;

        return ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft];
    }

    /**
     * Ищет список файлов на основе поисковой строки и входных параметров и возвращает его
     * @param string $searchQuery
     * @param string|null $userId
     * @param string|null $sortBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function searchAction(string $searchQuery = '', string $userId = null, string $sortBy = null, int $limit = null, int $offset = null): array
    {
        $filesList = $this->fileMapper->find(
            $userId,
            'user',
            $sortBy !== null && $sortBy !== '' ? $this->getOrderParams($sortBy)['orderBy'] : 'date',
            $sortBy !== null && $sortBy !== '' ? $this->getOrderParams($sortBy)['orderDir'] : 'DESC',
            $limit,
            $offset,
            $searchQuery
        );

        foreach ($filesList as $file) {
            $this->prepareFile($file);
        }

        $filesTotalQuantity = $this->fileMapper->count($userId, 'user', $searchQuery);

        $anyFilesLeft = ($filesTotalQuantity > ($offset + $limit)) ? true : false;

        return ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft];

    }

    /**
     * Удаляет файл из базы данных и диска
     * @param string $fileId
     */
    public function deleteAction(string $fileId): void
    {
        $fileToDelete = $this->fileMapper->findOne($fileId);
        $fileOwner = $fileToDelete->getUser();
        $filePath = $fileToDelete->getPath();
        $filePreview = $fileToDelete->getPreviewPath();

        $fullPath = "$this->filesDir/$fileOwner$filePath";
        $fullPreviewPath = "$this->filesDir/$fileOwner$filePreview";

         $this->fileMapper->delete($fileId);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        if (file_exists($fullPreviewPath)) {
            unlink($fullPreviewPath);
        }
    }

    /**
     * Подготавливает файл к выводу на экран
     * @param object $file
     */
    public function prepareFile(object $file): void
    {
        $file->setName($file->getName());
        $file->setSize($this->prepareSize($file->getSize()));
    }

    /**
     * Подготавливает имя файла к выводу на экран
     * @param string $name
     * @return string
     */
    private function prepareName(string $name): string
    {
        $maxNameLength = 32;

        if (strlen($name) <= $maxNameLength) return $name;

        $preparedName = preg_split('##u', $name, null, PREG_SPLIT_NO_EMPTY);

        for (;;) {
            $preparedName = implode('', $preparedName);
            $preparedName = preg_split('##u', $preparedName, null, PREG_SPLIT_NO_EMPTY);
            $middleLetter = round(count($preparedName) / 2) - 1;

            if (count($preparedName) <= $maxNameLength) {
                $preparedName[$middleLetter] = preg_replace('#.#', '...', $preparedName[$middleLetter]);
                $preparedName = implode('', $preparedName);
                break;
            } else {
                unset($preparedName[$middleLetter]);
                unset ($preparedName[$middleLetter + 1]);
            }
        }

        return $preparedName;
    }

    /**
     * Подготавливает размер файла к выводу на экран
     * @param int $size
     * @return string
     */
    private function prepareSize(int $size): string
    {
        if ($size < 100) {
            $preparedSize = $size;
            $unit = 'Bytes';
        } else if ($size < 1000000) {
            $preparedSize = round($size / 1000, 1);
            $unit = 'KB';
        } else {
            $preparedSize = round($size / 1000000, 1);
            $unit = 'MB';
        }

        return "Размер $preparedSize $unit";
    }


    /**
     * Формирует и возвращает SQL-понимаемые параметры для передачи в выражение
     * @param string $param
     * @return array
     */
    private function getOrderParams(string $param): array
    {
        $explodedParam = explode('_', $param);

        return ['orderBy' => $explodedParam[1], 'orderDir' => $explodedParam[2]];
    }
}