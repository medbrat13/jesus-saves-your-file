<?php

/**
 * Добавление зависимостей в контейнер
 */

use Foolz\SphinxQL\Drivers\Pdo\Connection as SphinxQLConnection;
use Foolz\SphinxQL\SphinxQL;
use JSYF\App\Controllers\Auth\RequestFigCookiesWrapper;
use JSYF\App\Controllers\Auth\ResponseFigCookiesWrapper;
use JSYF\App\Controllers\DownloadController;
use JSYF\App\Controllers\FilesController;
use JSYF\App\Controllers\UploadController;
use JSYF\App\Controllers\UserController;
use JSYF\App\Models\Mappers\FileMapper;
use JSYF\App\Models\Mappers\UserMapper;
use JSYF\Kernel\DB\Connection as MyConnection;
use JSYF\Kernel\DB\DBConfig;
use JSYF\Kernel\Exceptions\FileNotExistsException;
use JSYF\Kernel\Exceptions\FileNotFoundException;
use Pixie\Connection as PixieConnection;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Sphinx\SphinxClient;

$container['db_config'] = function () {
    $config = require_once ROOT . '/config/db_conf.php';
    return new DBConfig($config);
};

$container['connection'] = function ($c) {
    return new MyConnection($c['db_config']);
};

$container['view'] = function ($c) {
    $view = new Twig(ROOT . '/app/views', [
        'cache' => false
    ]);

    $basePath = str_replace('index.php', '', $c['request']->getUri()->getBasePath());
    $view->addExtension(new TwigExtension($c['router'], $basePath));

    return $view;
};

$container['request_cookies'] = function () {
    return new RequestFigCookiesWrapper();
};

$container['response_cookies'] = function () {
    return new ResponseFigCookiesWrapper();
};

$container['getid3'] = function () {
    return new getID3();
};

$container['query_builder'] = function ($c) {
    $connection = new PixieConnection($c['db_config']->driver, [
        'driver'   => $c['db_config']->driver,
        'host'     => $c['db_config']->host,
        'database' => $c['db_config']->dbName,
        'username' => $c['db_config']->user,
        'password' => $c['db_config']->pass
    ]);

    return new QueryBuilderHandler($connection);
};

$container['sphinx'] = function ($c) {
    $config = require_once ROOT . '/config/sphinx_conf.php';

    $sphinx = new SphinxClient();
    $sphinx->setServer($config['host'], $config['port']);
    $sphinx->setArrayResult(true);

    return $sphinx;
};

$container['sphinxql_query_builder'] = function ($c) {
    $config = require ROOT . '/config/sphinxQL_conf.php';

    $connection = new SphinxQLConnection();
    $connection->setParams(['host' => $config['host'], 'port' => $config['port']]);

    return new SphinxQL($connection);
};

$container['AuthController'] = function ($c) {
    return new JSYF\App\Controllers\Auth\AuthController($c['request'], $c['response'], $c['request_cookies'], $c['response_cookies']);
};

$container['UserController'] = function ($c) {
    return new UserController($c['response'], $c['response_cookies']);
};

$container['FilesController'] = function ($c) {
    return new FilesController($c['FileMapper']);
};

$container['DownloadController'] = function ($c) {
    return new DownloadController($c['FileMapper'], $c['response'], $c['FileNotExistsHandler']);
};

$container['UploadController'] = function ($c) {
    return new UploadController($c['getid3'], $c['FileMapper'], $c['UserMapper']);
};

$container['FileMapper'] = function ($c) {
    return new FileMapper($c['connection'], $c['query_builder'], $c['sphinx'], $c['sphinxql_query_builder'], $c['FileNotFoundHandler']);
};

$container['UserMapper'] = function ($c) {
    return new UserMapper($c['connection'], $c['query_builder'], $c['sphinx']);
};

$container['FileNotExistsHandler'] = function ($c) {
    return new FileNotExistsException();
};

$container['FileNotFoundHandler'] = function ($c) {
    return new FileNotFoundException();
};

$container['NotFoundHandler'] = function ($c) {
    return function ($request, $response) {
        return $response->withHeader('X-Accel-Redirect', "/errors/page-404.html")
            ->withHeader('Content-Disposition', 'inline')
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(404);
    };
};