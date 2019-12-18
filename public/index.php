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

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
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
    }

    $response = $next($request, $response);
    return $response;
});


# ГЛАВНАЯ
$app->get('/', function (Request $request, Response $response) {
    return $this->view->render($response, '/templates/index.twig');
})->setName('');


# ФАЙЛЫ
$app->get('/files', function (Request $request, Response $response, array $args) {

    $cookieUserId = FigRequestCookies::get($request, 'user')->getValue();

    # устанавливаем куки, если не установлены
    if ($cookieUserId === null) {
        $response = FigResponseCookies::set(
            $response,
            SetCookie::create('user')->withValue(uniqid('id'))->rememberForever()
        );
        $this->UserController->generateAvatar(FigResponseCookies::get($response, 'user')->getValue());
    }

    $params = $request->getQueryParams();

    # скачиваем файл
    if (array_key_exists('download_file_path', $params) && $params['download_file_path']) {
        $response = $this->DownloadController->downloadFile($params['download_file_path']);

        return $response;
    }

    # инициализируем переменные для передачи в контроллер
    $filesOwnerParam = $params['files'] ?? null;
    $userId = $filesOwnerParam === 'mine' ? $cookieUserId : null;
    $sortByParam = $params['sort'] ?? '';
    $offset = $request->getParams()['offset'] ?? 0;
    $limit = 6;

    # поисковой запрос
    $searchQuery = $params['search'] ?? '';

    if ($request->hasHeader('HTTP_X_REQUESTED_WITH') && $searchQuery !== '') {
        # данные, если пользователь что-то ищет
        $filesData = $this->FilesController->searchAction($searchQuery, $userId, $sortByParam, $limit, $offset);
    } else {
        # данные, если пользователь ничего не ищет
        $filesData = $this->FilesController->indexAction($userId, $sortByParam, $limit, $offset);
    }

    # доп. инфа для подгрузки файлов через ajax
    $filesList = $filesData['files'];
    $anyFilesLeft = $filesData['anyFilesLeft'];

    # подгрузка файлов по ajax
    if ($request->hasHeader('HTTP_X_REQUESTED_WITH')) {
        return $this->view->render($response, '/templates/files-list.twig',
            ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft , 'myId' => $cookieUserId]);
    }

    return $this->view->render($response, '/templates/files.twig',
        ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft, 'myId' => $cookieUserId]);
})->setName('files');


$app->post('/files', function (Request $request, Response $response, array $args) {

    # ajax
    if ($request->hasHeader('HTTP_X_REQUESTED_WITH')) {

        # проверка на существование иконки файла
        if (array_key_exists('icon', $request->getParams())) {
            $response = $response->write(is_file(ROOT . '/public' . $request->getParams()['icon']));
            return $response;
        }

        # сохраняем файл в базу
        if (array_key_exists('file', $request->getUploadedFiles())) {
            $file = $request->getUploadedFiles()['file'];
            $cookieUserId = FigRequestCookies::get($request, 'user')->getValue();

            $params = [
                'user'    => $cookieUserId,
            ];

            if ($file->getError() === UPLOAD_ERR_OK) {
                $fileId = $this->UploadController->uploadAction($file, $params);
                $file = $this->FileMapper->findOne($fileId);
                $this->FilesController->prepareFile($file);

                $this->view->render($response, 'templates/file.twig', ['file' => $file, 'myId' => $cookieUserId]);
            } else {
                $response->write(0);
            }
        }

        # удаляем файл из базы и файловой системы
        if (array_key_exists('delete-file-id', $request->getParams())) {
            $deleteFileId = $request->getParams()['delete-file-id'] ?? null;
            $this->FilesController->deleteAction($deleteFileId);
        }
    }

    return $response;

})->setName('files');

$app->run();
