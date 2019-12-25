<?php

namespace JSYF\App\Controllers;

use JSYF\App\Controllers\Auth\AuthController;
use JSYF\App\Models\Entities\File;
use JSYF\App\Models\Mappers\FileMapper;

/**
 * Контроллер для работы с файлами
 */
class FilesController
{
    /**
     * @var string Id пользователя
     */
    private $userId;

    /**
     * @var string Установленный параметр владельца файла
     */
    private $filesOwnerParam;

    /**
     * @var string Метод сортировки
     */
    private $sortByParam;

    /**
     * @var int Смещение
     */
    private $offset;

    /**
     * @var int Лимит
     */
    private $limit;

    /**
     * @var string Поисковой запрос
     */
    private $searchQuery;

    /**
     * @var AuthController
     */
    private $authController;

    /**
     * @var HttpController
     */
    private $httpController;

    /**
     * @var FileMapper
     */
    private $fileMapper;

    /**
     * @var string Фалойвая директория
     */
    private $filesDir = ROOT . '/files';

    public function __construct(AuthController $authController, HttpController $httpController, FileMapper $fileMapper)
    {
        $this->authController = $authController;
        $this->httpController = $httpController;
        $this->fileMapper = $fileMapper;

        $params = $this->httpController->getParams();
        $this->filesOwnerParam = $params[$this->httpController::GETParamFilesMarker] ?? null;
        $this->userId = ($this->filesOwnerParam === $this->httpController::GETValueShowMyFiles) ? $this->authController->getUserId() : null;
        $this->sortByParam = $params[$this->httpController::GETParamSortMarker] ?? '';
        $this->offset = $params[$this->httpController::GETParamOffsetMarker] ?? 0;
        $this->limit = 6;
        $this->searchQuery = $params[$this->httpController::GETParamSearchMarker] ?? '';
    }

    /**
     * Ищет список файлов и возвращает его
     * @return array
     */
    public function indexAction(): array
    {
        $filesList = $this->fileMapper->find(
            $this->userId,
            'user',
            $this->sortByParam !== null && $this->sortByParam !== '' ? $this->getOrderParams($this->sortByParam)['orderBy'] : 'date',
            $this->sortByParam !== null && $this->sortByParam !== '' ? $this->getOrderParams($this->sortByParam)['orderDir'] : 'DESC',
            $this->limit,
            $this->offset
        );

        foreach ($filesList as $file) {
            $this->prepareFileToRender($file);
        }

        $filesTotalQuantity = $this->fileMapper->count($this->userId, 'user');

        $anyFilesLeft = ($filesTotalQuantity > ($this->offset + $this->limit)) ? true : false;

        return ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft];
    }

    /**
     * Ищет список файлов на основе поисковой строки и возвращает его
     * @return array
     */
    public function searchAction(): array
    {
        $filesList = $this->fileMapper->find(
            $this->userId,
            'user',
            $this->sortByParam !== null && $this->sortByParam !== '' ? $this->getOrderParams($this->sortByParam)['orderBy'] : 'date',
            $this->sortByParam !== null && $this->sortByParam !== '' ? $this->getOrderParams($this->sortByParam)['orderDir'] : 'DESC',
            $this->limit,
            $this->offset,
            $this->searchQuery
        );

        foreach ($filesList as $file) {
            $this->prepareFileToRender($file);
        }

        $filesTotalQuantity = $this->fileMapper->count($this->userId, 'user', $this->searchQuery);

        $anyFilesLeft = ($filesTotalQuantity > ($this->offset + $this->limit)) ? true : false;

        return ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft];
    }

    /**
     * Удаляет файл из базы данных и диска
     */
    public function deleteAction(): void
    {
        $fileId = $this->getFileIdToDelete();
        $fileToDelete = $this->fileMapper->findOne($fileId);
        $fileOwner = $fileToDelete->getUser();
        $filePath = $fileToDelete->getPath();
        $filePreview = $fileToDelete->getPreviewPath();

        $fullPath = "$this->filesDir/$fileOwner/$filePath";
        $fullPreviewPath = "$this->filesDir/$fileOwner/$filePreview";

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
     * @param File $file
     * @return File
     */
    public function prepareFileToRender(File $file): File
    {
        $file = clone $file;
        $file->setName($this->prepareName($file->getName()));
        $file->setSize($this->prepareSize($file->getSize()));

        return $file;
    }

    /**
     * Возвращает ID удаляемого файла
     * @return int|null
     */
    public function getFileIdToDelete(): ?int
    {
        return $this->httpController->getBody()[$this->httpController::DTODeleteFileMarker] ?? null;
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