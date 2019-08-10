<?php

namespace JSYF\Kernel\Exceptions;

use Exception;
use Throwable;

/**
 * Исключение выпадает, когда файл не существует в файловой системе
 */
class FileNotExistsException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}