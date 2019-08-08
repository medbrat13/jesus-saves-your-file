<?php
/*
/**
 * Фронт-контроллер приложения
 */

mb_regex_encoding('UTF-8');
mb_internal_encoding("UTF-8");

define('ROOT', dirname(__DIR__));

require ROOT . '/vendor/autoload.php';
require ROOT . '/vendor/gigablah/sphinxphp/src/Sphinx/SphinxClient.php';

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use JSYF\App\Controllers\DownloadController;
use JSYF\App\Controllers\FilesController;
use JSYF\App\Controllers\UploadController;
use JSYF\App\Models\Mappers\FilesMapper;
use JSYF\Kernel\DB\Connection as MyConnection;
use JSYF\Kernel\DB\DBConfig;
use Pixie\Connection as PixieConnection;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Sphinx\SphinxClient;

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
    $config = require ROOT . '/config/db_conf.php';
    $db_config = new DBConfig($config);

    return $db_config;
};

$container['connection'] = function ($c) {
    $connection = new MyConnection($c['db_config']);

    return $connection;
};

$container['query_builder'] = function () {
    $config = require ROOT . '/config/db_conf.php';

    $connection = new PixieConnection('pgsql', [
        'driver'   => $config['driver'],
        'host'     => $config['host'],
        'database' => $config['dbname'],
        'username' => $config['user'],
        'password' => $config['pass']
    ]);

    $builder = new QueryBuilderHandler($connection);

    return $builder;
};

$container['sphinx'] = function () {
    $config = require_once ROOT . '/config/sphinx_conf.php';

    $sphinx = new SphinxClient();
    $sphinx->setServer($config['host'], $config['port']);
    $sphinx->setArrayResult(true);

    return $sphinx;
};

$container['getid3'] = function () {
    $getid3 = new getID3();

    return $getid3;
};

$container['FilesMapper'] = function ($c) {
    $controller = new FilesMapper($c['connection'], $c['query_builder'], $c['sphinx']);

    return $controller;
};

$container['UploadController'] = function ($c) {
    $controller = new UploadController($c['getid3'], $c['FilesMapper']);

    return $controller;
};

$container['DownloadController'] = function ($c) {
    $controller = new DownloadController($c['FilesMapper'], $c['response']);

    return $controller;
};

$container['FilesController'] = function ($c) {
    $controller = new FilesController($c['FilesMapper']);

    return $controller;
};


# ГЛАВНАЯ
$app->get('/', function (Request $request, Response $response) {

    $userId = FigRequestCookies::get($request, 'user')->getValue();

    # устанавливаем куки, если не установлены
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

    # устанавливаем куки, если не установлены
    if ($userId === NULL) {
        $response = FigResponseCookies::set(
            $response,
            SetCookie::create('user')->withValue(uniqid('id'))->rememberForever()
        );
    }

    $params = $request->getQueryParams();

    # скачиваем файл
    if (array_key_exists('download_file_path', $params) && $params['download_file_path']) {
        $response = $this->DownloadController->downloadFile($params['download_file_path']);
        return $response;
    }

    # инициализируем переменные для передачи в контроллер
    $filesOwnerParam = $params['files'] ?? null;
    $userId = $filesOwnerParam === 'mine' ? $userId : null;
    $sortByParam = $params['sort'] ?? '';
    $offset = $request->getParams()['offset'] ?? 0;
    $limit = 6;

    # поисковой запрос
    $searchQuery = $params['search'] ?? '';

    if ($searchQuery !== '') {
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
        return $this->view->render($response, '/templates/files-list.twig', ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft]);
    }

    return $this->view->render($response, '/templates/files.twig', ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft]);
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
