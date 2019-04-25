<?php

namespace JSYF\App\Controllers;

use JSYF\Kernel\Base\Controller;

/**
 * Контроллер для работы с файлами
 */
class FilesController extends Controller
{
    public function indexAction(string $sortBy): array
    {

        return $this->data;
    }

}