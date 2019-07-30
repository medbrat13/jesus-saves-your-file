<?php

namespace JSYF\App\Controllers;

use JSYF\App\Models\Mappers\FilesMapper;
use Slim\Http\Response;

/**
 * Класс, отвечающий за скачивание файлов
 */
class DownloadController
{
    /**
     * @var FilesMapper
     */
    private $filesMapper;

    /**
     * @var Response
     */
    private $response;

    public function __construct(FilesMapper $filesMapper, Response $response)
    {
        $this->filesMapper = $filesMapper;
        $this->response = $response;
    }

    /**
     * Получает на вход путь к переименованному файлу
     * и дает пользователю скачать файл с его оригинальным именем
     * @param string $filePath Путь к файлу на диске
     * @return Response
     * @throws \Exception
     */
    public function downloadFile(string $filePath): Response
    {

        if (file_exists(ROOT . '/files/' . $filePath)) {

            $pathWithoutUserPrefix = preg_replace('/^id[a-zA-Z0-9]+/', '', $filePath);
            $file = $this->filesMapper->findOne($pathWithoutUserPrefix, 'path');

            $urlEncodedFileName = urlencode($file->getName());
            $finalFileName = str_replace('+', ' ', $urlEncodedFileName);

            $this->response = $this->response->withHeader('X-Accel-Redirect', "/files/$filePath")
                ->withHeader('Content-Type', 'application/octet-stream')
                ->withHeader('Content-Disposition', "attachment; filename=$finalFileName");

            return $this->response;
        } else {
            throw new \Exception('Ошибка: файл не найден :(');
        }
    }
}