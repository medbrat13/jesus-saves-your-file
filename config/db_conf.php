<?php

/**
 * Настройки подключения к базе данных
 */

return [
    'driver' => 'pgsql',
    'host' => 'localhost',
    'dbname' => 'jsyf',
    'user' => 'user_name',
    'pass' => 'your_password',
    'opt'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];