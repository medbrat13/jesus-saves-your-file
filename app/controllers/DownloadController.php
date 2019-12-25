<?php

namespace JSYF\App\Controllers;

use JSYF\App\Models\Mappers\FileMapper;
use JSYF\Kernel\Exceptions\FileNotExistsException;
use Slim\Http\Response;

/**
 * Класс, отвечающий за скачивание файлов
 */
class DownloadController
{
    /**
     * @var HttpController
     */
    private $httpController;

    /**
     * @var FileMapper
     */
    private $fileMapper;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var FileNotExistsException
     */
    private $fileNotExistsException;

    public function __construct(HttpController $httpController, FileMapper $fileMapper, Response $response, FileNotExistsException $fileNotExistsException)
    {
        $this->httpController = $httpController;
        $this->fileMapper = $fileMapper;
        $this->response = $response;
        $this->fileNotExistsException = $fileNotExistsException;
    }

    /**
     * Скачивает файл
     * @return Response
     */
    public function downloadFile(): Response
    {
        $filePath = $this->httpController->getParams()[$this->httpController::DOWNLOAD_FILE_MARKER];

        try {
            if (file_exists(ROOT . '/files/' . $filePath)) {

                $pathWithoutUserPrefix = trim(preg_replace('/^id[a-zA-Z0-9]+/', '', $filePath), '/');
                $file = $this->fileMapper->findOne($pathWithoutUserPrefix, 'path');

                $urlEncodedFileName = urlencode($file->getName());

                $finalFileName = str_replace('+', ' ', $urlEncodedFileName);

                $this->response = $this->response->withHeader('X-Accel-Redirect', "/files/$filePath")
                    ->withHeader('Content-Type', 'application/octet-stream')
                    ->withHeader('Content-Disposition', "attachment; filename=$finalFileName");

                return $this->response;
            } else {
                throw $this->fileNotExistsException;
            }
        } catch (FileNotExistsException $exception) {
            $this->response = $this->response->withHeader('X-Accel-Redirect', "/errors/file-404.html")
                ->withHeader('Content-Disposition', 'inline')
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(404);
             return $this->response;
        }
    }
}