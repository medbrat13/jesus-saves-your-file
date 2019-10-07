<?php

/**
 * Список сервисов
 */

return [
    \JSYF\Kernel\Services\NotFoundHandlerServiceProvider::class,
    \JSYF\Kernel\Services\TwigViewServiceProvider::class,
    \JSYF\Kernel\Services\DBConfigServiceProvider::class,
    \JSYF\Kernel\Services\ConnectionServiceProvider::class,
    \JSYF\Kernel\Services\QueryBuilderServiceProvider::class,
    \JSYF\Kernel\Services\SphinxServiceProvider::class,
    \JSYF\Kernel\Services\SphinxqlQueryBuilderServiceProvider::class,
    \JSYF\Kernel\Services\Getid3ServiceProvider::class,
    \JSYF\Kernel\Services\FileMapperServiceProvider::class,
    \JSYF\Kernel\Services\UserMapperServiceProvider::class,
    \JSYF\Kernel\Services\UploadControllerServiceProvider::class,
    \JSYF\Kernel\Services\DownloadControllerServiceProvider::class,
    \JSYF\Kernel\Services\FilesControllerServiceProvider::class,
    \JSYF\Kernel\Services\UserControllerServiceProvider::class,
    \JSYF\Kernel\Services\FileNotFoundHandlerServiceProvider::class,
    \JSYF\Kernel\Services\FileNotExistsHandlerServiceProvider::class
];