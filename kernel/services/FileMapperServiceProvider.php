<?php

namespace JSYF\Kernel\Services;

use JSYF\App\Models\Mappers\FileMapper;

class FileMapperServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['FileMapper'] = function ($c) {
            $mapper = new FileMapper(
                $c['connection'],
                $c['query_builder'],
                $c['sphinx'],
                $c['sphinxql_query_builder'],
                $c['FileNotFoundHandler']
            );

            return $mapper;
        };
    }
}