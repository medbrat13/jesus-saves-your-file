<?php


namespace JSYF\App\Controllers;

use getID3;
use Slim\Http\UploadedFile;

/**
 * Контроллер главной страницы
 */
class UploadController
{
    /**
     * @var getID3
     */
    private $getid3;

    public function __construct(getID3 $getid3)
    {
        $this->getid3 = $getid3;
    }

    public function uploadAction(UploadedFile $file)
    {
        $this->getid3->encoding = 'UTF-8';
        $fileInfo = $this->getid3->analyze($file->file);

        $realName = $file->getClientFilename();
        $filesize = $fileInfo['filesize'];

        if (array_key_exists('video', $fileInfo)) {
            $res = $fileInfo['video']['resolution_x'] . 'x' . $fileInfo['video']['resolution_y'];
        } else {
            $res = false;
        }

        if (array_key_exists('playtime_string', $fileInfo)) {
            $dur = $fileInfo['playtime_string'];
        } else {
            $dur = false;
        }

        return [
            'name' => $realName,
            'size' => $filesize,
            'resolution' => $res,
            'duration' => $dur
        ];
    }

    /**
     * Перемещает файл из временного хранилища в постоянное
     *
     * @param UploadedFile $file
     * @param $root
     * @param $username
     * @param string $album
     * @return string
     * @throws \Exception
     */
    public function saveFileAction(UploadedFile $file, $root, $username, $album = 'default'): string
    {
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $albumName = $album;
        $subAlbumName = date('Y-m-d');
        $albumPath = $root . DIRECTORY_SEPARATOR . $username . DIRECTORY_SEPARATOR . $albumName . DIRECTORY_SEPARATOR . $subAlbumName;

        if (!file_exists($albumPath)) {
            mkdir($albumPath, 0777, true);
        }

        if ($this->isImage($file)) {
            $this->makeImgPreview($file->file, 'preview-' . $filename, $file->getClientMediaType(), $albumPath);
        }

        $file->moveTo($albumPath . DIRECTORY_SEPARATOR . $filename);

        return $albumPath . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Создает уменьшенную копию изображения
     *
     * @param $img
     * @param string $name
     * @param string $type
     * @param string $dest
     * @return bool
     */
    private function makeImgPreview($img, string $name, string $type, string $dest): bool
    {
        $format = function () use ($type): string
        {
            switch ($type) {
                case 'image/jpeg': $format = 'jpeg'; break;
                case 'image/png': $format = 'png'; break;
                case 'image/gif': $format = 'gif'; break;
                default: $format = '';
            }

            return $format;
        };

        $icfunc = 'imagecreatefrom' . $format();

        if (!function_exists($icfunc)) return false;

        $originalSource = $icfunc($img);

        $originalWidth = imagesx($originalSource);
        $originalHeight = imagesy($originalSource);

        $relativeWidth = 60;

        $coefficient = round($originalWidth / $relativeWidth, 2);

        $previewWidth = $originalWidth / $coefficient;
        $previewHeight = $originalHeight / $coefficient;

        $preview = imagecreatetruecolor($previewWidth, $previewHeight);
        imagefill($preview, 0, 0, 0xffffff);
        imagecopyresampled($preview, $originalSource, 0, 0, 0, 0, $previewWidth, $previewHeight, $originalWidth, $originalHeight);

        $saveImgFunc = 'image' . $format();

        if ($format() === 'jpeg') {
            $saveImgFunc($preview, $dest . '/' . $name, 100);
        } else if ($format() === 'png') {
            $saveImgFunc($preview, $dest . '/' . $name, 0);
        } else if ($format() === 'gif') {
            $saveImgFunc($preview, $dest . '/' . $name);
        }

        imagedestroy($originalSource);
        imagedestroy($preview);

        return true;
    }

    private function isImage(UploadedFile $file): bool
    {
        if (
            $file->getClientMediaType() === 'image/gif' ||
            $file->getClientMediaType() === 'image/jpeg' ||
            $file->getClientMediaType() === 'image/png'
        ) return true;

        return false;
    }
}