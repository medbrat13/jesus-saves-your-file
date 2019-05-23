<?php
/*
/**
 * Фронт-контроллер приложения
 */

mb_regex_encoding('UTF-8');
mb_internal_encoding("UTF-8");

define('ROOT', dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use JSYF\Kernel\Helpers\QueryBuilder;
use JSYF\App\Controllers\FilesController;
use JSYF\App\Controllers\UploadController;
use JSYF\App\Models\Mappers\FilesMapper;
use JSYF\Kernel\DB\Connection;
use JSYF\Kernel\DB\DBConfig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$app = new App($config);

# КОНТЕЙНЕР
$container = $app->getContainer();

$container['view'] = function ($c) {
    $view = new Twig(ROOT . '/app/views', [
        'cache' => false
    ]);

    $basePath = str_replace('index.php', '', $c['request']->getUri()->getBasePath());
    $view->addExtension(new TwigExtension($c['router'], $basePath));

    return $view;
};

$container['db_config'] = function () {
    $config = require_once ROOT . '/config/db_conf.php';
    $db_config = new DBConfig($config);

    return $db_config;
};

$container['connection'] = function ($c) {
    $connection = new Connection($c['db_config']);

    return $connection;
};

$container['query_builder'] = function () {
    $builder = new QueryBuilder();

    return $builder;
};

$container['getid3'] = function () {
    $getid3 = new getID3();

    return $getid3;
};

$container['FilesMapper'] = function ($c) {
    $controller = new FilesMapper($c['connection'], $c['query_builder']);

    return $controller;
};

$container['UploadController'] = function ($c) {
    $controller = new UploadController($c['getid3'], $c['FilesMapper']);

    return $controller;
};

$container['FilesController'] = function ($c) {
    $controller = new FilesController($c['FilesMapper']);

    return $controller;
};


# ГЛАВНАЯ
$app->get('/', function (Request $request, Response $response) use ($app) {

    $userId = FigRequestCookies::get($request, 'user');

    if ($userId === NULL) {
        $response = FigResponseCookies::set(
            $response,
            SetCookie::create('user')->withValue(uniqid('id'))->rememberForever()
        );
    }

    return $this->view->render($response, '/templates/index.twig');
})->setName('');


# ФАЙЛЫ
$app->get('/files', function (Request $request, Response $response, array $args) {

    $userId = FigRequestCookies::get($request, 'user')->getValue();

    if ($userId === NULL) {
        $response = FigResponseCookies::set(
            $response,
            SetCookie::create('user')->withValue(uniqid('id'))->rememberForever()
        );
    }

    $params = $request->getQueryParams();
    $filesParam = $params['files'] ?? '';
    $sortByParam = $params['sort'] ?? '';
    $offset = $request->getParams()['offset'] ?? 0;
    $limit = 6;

    $filesData = $this->FilesController->indexAction($filesParam, $sortByParam, $userId, $limit, $offset);
    $files = $filesData['files'];
    $anyFilesLeft = $filesData['anyFilesLeft'];

    if ($request->hasHeader('HTTP_X_REQUESTED_WITH')) {

        if (count($files) === 0) return $this->view->render($response, '/templates/files-list.twig', ['files' => $files]);

        return $this->view->render($response, '/templates/files-list.twig', ['files' => $files, 'anyFilesLeft' => $anyFilesLeft]);
    }

    return $this->view->render($response, '/templates/files.twig', ['files' => $files, 'anyFilesLeft' => $anyFilesLeft]);
})->setName('files');


$app->post('/files', function (Request $request, Response $response, array $args) {

    if ($request->hasHeader('HTTP_X_REQUESTED_WITH')) {

        if (array_key_exists('icon', $request->getParams())) {
            $response = $response->write(is_file(ROOT . '/public' . $request->getParams()['icon']));
            return $response;
        }

        if (array_key_exists('file', $request->getUploadedFiles())) {
            $file = $request->getUploadedFiles()['file'];
            $userId = FigRequestCookies::get($request, 'user')->getValue();
            $album = $request->getParams()['album'];
            $comment = $request->getParams()['comment'];

            $params = [
                'user'    => $userId,
                'album'   => $album,
                'comment' => $comment
            ];

            if ($file->getError() === UPLOAD_ERR_OK) {
                $fileId = $this->UploadController->uploadAction($file, $params);
                $file = $this->FilesMapper->findOne($fileId);
                $this->FilesController->prepareFile($file);

                $this->view->render($response, 'templates/file.twig', ['file' => $file]);
            } else {
                $response->write(0);
            }
        }
    }

    return $response;

})->setName('files');

$app->run();
