<?php

namespace JSYF\Kernel\Exceptions;

use Exception;
use Slim\Http\Response;
use Throwable;

/**
 * Исключение, выпадающее, когда файл не найден в базе данных
 */
class FileNotFoundException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}