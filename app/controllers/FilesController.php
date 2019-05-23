<?php

namespace JSYF\App\Controllers;

use JSYF\App\Models\Mappers\FilesMapper;

/**
 * Контроллер для работы с файлами
 */
class FilesController
{
    /**
     * @var FilesMapper
     */
    private $filesMapper;

    public function __construct(FilesMapper $filesMapper)
    {
        $this->filesMapper = $filesMapper;
    }

    public function indexAction(string $files = null, string $sortBy = null, string $userId = null, int $limit = null, int $offset = null): array
    {
        $filesList = $this->filesMapper->find(
            '*',
            'files',
            "%$userId%",
            $files === 'mine' ? ['"user"'] : [],
            $sortBy !== null && $sortBy !== '' ? $this->getOrderParams($sortBy)['orderBy'] : 'date',
            $sortBy !== null && $sortBy !== '' ? $this->getOrderParams($sortBy)['orderDir'] : 'DESC',
            $limit,
            $offset
        );

        foreach ($filesList as $file) {
            $this->prepareFile($file);
        }

        $filesTotalQuantity = $this->filesMapper->count(
            '*',
            'files',
            "%$userId%",
            $files === 'mine' ? ['"user"'] : []
        );

        $anyFilesLeft = ($filesTotalQuantity >= ($offset + $limit)) ? true : false;

        return ['files' => $filesList, 'anyFilesLeft' => $anyFilesLeft];
    }

    /**
     * Подготавливает файл к выводу на экран
     *
     * @param object $file
     */
    public function prepareFile(object $file): void
    {
        $file->setName($this->prepareName($file->getName()));
        $file->setSize($this->prepareSize($file->getSize()));
        $file->setDate($this->prepareDate($file->getDate()));
    }

    /**
     * Подготавливает имя файла к выводу на экран
     *
     * @param string $name
     * @return string
     */
    private function prepareName(string $name): string
    {
        $maxNameLength = 23;

        if (strlen($name) <= $maxNameLength) return $name;

        $preparedName = preg_split('##u', $name, NULL, PREG_SPLIT_NO_EMPTY);

        for (;;) {
            $preparedName = implode('', $preparedName);
            $preparedName = preg_split('##u', $preparedName, NULL, PREG_SPLIT_NO_EMPTY);;
            $middleLetter = round(count($preparedName) / 2) - 1;

            if (count($preparedName) <= $maxNameLength) {
                $preparedName[$middleLetter] = preg_replace('#.#', '...', $preparedName[$middleLetter]);
                $preparedName = implode('', $preparedName);
                break;
            } else {
                unset($preparedName[$middleLetter]);
                unset ($preparedName[$middleLetter + 1]);
            }
        }

        return $preparedName;
    }

    /**
     * Подготавливает размер файла к выводу на экран
     *
     * @param int $size
     * @return string
     */
    private function prepareSize(int $size): string
    {
        if ($size < 100) {
            $preparedSize = $size;
            $unit = 'Bytes';
        } else if ($size < 1000000) {
            $preparedSize = round($size / 1000, 1);
            $unit = 'KB';
        } else {
            $preparedSize = round($size / 1000000, 1);
            $unit = 'MB';
        }

        return "Размер $preparedSize $unit";
    }

    /**
     * Подготавливает дату к выводу на экран
     *
     * @param string $date
     * @return string
     */
    private function prepareDate(string $date): string
    {
        $dateWithoutMilliSecs = explode('.', $date)[0];
        $dateAndTime = explode(' ', $dateWithoutMilliSecs);

        $outputDate = implode('-', array_reverse(explode('-', $dateAndTime[0])));

        $tmpTime = explode(':', $dateAndTime[1]);
        unset($tmpTime[2]);
        $outputTime = implode(':', $tmpTime);

        return "Загружено $outputTime $outputDate";
    }

    /**
     * Формирует и возвращает SQL-понимаемые параметры для передачи в выражение
     *
     * @param string $param
     * @return array
     */
    private function getOrderParams(string $param): array
    {
        $explodedParam = explode('_', $param);

        return ['orderBy' => $explodedParam[1], 'orderDir' => $explodedParam[2]];
    }
}