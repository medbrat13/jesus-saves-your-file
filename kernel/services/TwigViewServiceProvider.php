<?php

namespace JSYF\Kernel\Services;

use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class TwigViewServiceProvider extends AbstractServiceProvider
{
    /**
    * Создает сервис
    * @return void
    */
    public function init(): void
    {
        $this->di['view'] = function ($c) {
            $view = new Twig(ROOT . '/app/views', [
                'cache' => false
            ]);

            $basePath = str_replace('index.php', '', $c['request']->getUri()->getBasePath());
            $view->addExtension(new TwigExtension($c['router'], $basePath));

            return $view;
        };
    }
}