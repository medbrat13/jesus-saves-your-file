<?php

namespace JSYF\App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\UploadedFile;

/**
 * Класс, отвечающий за http-запросы
 */
class HttpController
{
    /**
     * Маркер запроса скачивания файла со стороны клиента
     */
    public const DOWNLOAD_FILE_MARKER = 'download_file_path';

    /**
     * Маркер Data Transfer Object для загрузки файла
     */
    private const DTOFileMarker = 'file';

    /**
     * Маркер Data Transfer Object для удаления файла
     */
    public const DTODeleteFileMarker = 'delete-file-id';

    /**
     * Название GET-параметра владельца файла
     */
    public const GETParamFilesMarker = 'files';

    /**
     * Название GET-параметра сортировки
     */
    public const GETParamSortMarker = 'sort';

    /**
     * Название GET-параметра поиска
     */
    public const GETParamSearchMarker = 'search';

    /**
     * Название GET-параметра смещения
     */
    public const GETParamOffsetMarker = 'offset';

    /**
     * Значение GET-параметра: показать все файлы
     */
    public const GETValueShowAllFiles = 'all';

    /**
     * Значение GET-параметра: показать только мои файлы
     */
    public const GETValueShowMyFiles = 'mine';

    /**
     * @var ServerRequestInterface
     */
    private $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Определяет, является ли запрос ajax'ом
     * @return bool
     */
    public function isAjax(): bool
    {
        return $this->request->hasHeader('HTTP_X_REQUESTED_WITH');
    }

    /**
     * Определяет, был ли запрос со стороны клиента о скачивании файла
     * @return bool
     */
    public function headerForDownloadFileExists(): bool
    {
        $params = $this->request->getQueryParams();

        return (array_key_exists(self::DOWNLOAD_FILE_MARKER, $params) && $params[self::DOWNLOAD_FILE_MARKER]);
    }

    /**
     * Определяет, есть ли поисковой запрос в GET-строчке
     * @return bool
     */
    public function isThereSearchQuery(): bool
    {
        $params = $this->request->getQueryParams();

        return $params[self::GETParamSearchMarker] !== '' ? true : false;
    }

    /**
     * Проверяет, есть ли файл для загрузки
     * @return bool
     */
    public function headerForUploadFileExists(): bool
    {
        return array_key_exists(self::DTOFileMarker, $this->request->getUploadedFiles());
    }

    /**
     * Определяет, пришло ли требование с фронтенда об удалении файла
     * @return bool
     */
    public function headerForDeleteFileExists(): bool
    {
        return array_key_exists(self::DTODeleteFileMarker, $this->request->getParsedBody());
    }

    /**
     * Определяет, без ошибок ли загрузился файл
     * @return bool
     */
    public function uploadWithoutError(): bool
    {
        return $this->getFileFromGlobals()->getError() === UPLOAD_ERR_OK ? true : false;
    }

    /**
     * Возвращает файл из глобального массива
     * @return UploadedFile
     */
    public function getFileFromGlobals(): UploadedFile
    {
        return $this->request->getUploadedFiles()[self::DTOFileMarker];
    }

    /**
     * Обертка над Request->getQueryParams()
     * @return array
     */
    public function getParams(): array
    {
        return $this->request->getQueryParams();
    }

    /**
     * Обертка над Request->getParsedBody()
     * @return array
     */
    public function getBody(): array
    {
        return $this->request->getParsedBody();
    }
}