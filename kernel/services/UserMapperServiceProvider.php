<?php

namespace JSYF\Kernel\Services;

use JSYF\App\Models\Mappers\UserMapper;

class UserMapperServiceProvider extends AbstractServiceProvider
{
    /**
     * Создает сервис
     * @return void
     */
    public function init(): void
    {
        $this->di['UserMapper'] = function ($c) {
            $mapper = new UserMapper($c['connection'], $c['query_builder'], $c['sphinx']);

            return $mapper;
        };
    }
}