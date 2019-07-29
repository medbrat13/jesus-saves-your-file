<?php
/**
 * Настройки подключения к базе данных
 */

return [
    'driver' => 'pgsql',
    'host' => '127.0.0.1',
    'port' => '5432',
    'dbname' => 'jsyf',
    'user' => 'user',
    'pass' => 'pass',
    'opt'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];