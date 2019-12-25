<?php
/*
/**
 * Фронт-контроллер приложения
 */

mb_regex_encoding('UTF-8');
mb_internal_encoding("UTF-8");

define('ROOT', dirname(__DIR__));

require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/config/app_conf.php';
require_once ROOT . '/vendor/gigablah/sphinxphp/src/Sphinx/SphinxClient.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

$app = new App($app_config);

require_once ROOT . '/config/container.php';

# АВТОРИЗАЦИЯ (MIDDLEWARE)
$app->add(function ($request, $response, $next) {
    if (!$this->AuthController->isUserAuthorized()) {
        $response = $this->AuthController->signUp();
        $this->UserController->generateAvatar();
    } else {
        $this->AuthController->setUserIdFromCookies();
    }

    $response = $next($request, $response);
    return $response;
});


# ГЛАВНАЯ
$app->get('/', function (Request $request, Response $response) {
    return $this->view->render($response, '/templates/index.twig');
})->setName('');


# ФАЙЛЫ
$app->get('/files', function (Request $request, Response $response) {

    # скачиваем файл
    if ($this->HttpController->headerForDownloadFileExists()) {
        return $this->DownloadController->downloadFile();
    }

    if ($this->HttpController->isAjax() && $this->HttpController->isThereSearchQuery()) {
        # данные, если пользователь что-то ищет
        $filesData = $this->FilesController->searchAction();
    } else {
        # данные, если пользователь ничего не ищет
        $filesData = $this->FilesController->indexAction();
    }

    # подгрузка файлов по ajax
    if ($this->HttpController->isAjax()) {
        return $this->view->render($response, '/templates/files-list.twig',
            [
                'files' => $filesData['files'],
                'anyFilesLeft' => $filesData['anyFilesLeft'],
                'myId' =>  $this->AuthController->getUserId()
            ]
        );
    }

    # обычная загрузка страницы
    return $this->view->render($response, '/templates/files.twig',
        [
            'files' => $filesData['files'],
            'anyFilesLeft' => $filesData['anyFilesLeft'],
            'myId' =>  $this->AuthController->getUserId()
        ]
    );
})->setName('files');


$app->post('/files', function (Request $request, Response $response) {

    if ($this->HttpController->isAjax()) {

        # сохраняем файл в базу
        if ($this->HttpController->headerForUploadFileExists() && $this->HttpController->uploadWithoutError()) {
            $file = $this->UploadController->uploadAction();
            $file = $this->FilesController->prepareFileToRender($file);

            $this->view->render($response, 'templates/file.twig',
                [
                    'file' => $file,
                    'myId' => $this->AuthController->getUserId()
                ]
            );
        }

        # удаляем файл из базы и файловой системы
        if ($this->HttpController->headerForDeleteFileExists()) {
            $this->FilesController->deleteAction();
        } else {
            $this->response->write(999);
        }
    }

    return $response;

})->setName('files');

$app->run();