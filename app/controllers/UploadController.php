<?php


namespace JSYF\App\Controllers;

use getID3;
use JSYF\App\Controllers\Auth\AuthController;
use JSYF\App\Models\Entities\File;
use JSYF\App\Models\Entities\User;
use JSYF\App\Models\Mappers\FileMapper;
use JSYF\App\Models\Mappers\UserMapper;
use Slim\Http\UploadedFile;

/**
 * Контроллер главной страницы
 */
class UploadController
{
    /**
     * Каталог для загрузки файлов
     */
    private const UPLOAD_DIR = ROOT . '/files';

    /**
     * @var getID3
     */
    private $getid3;

    /**
     * @var HttpController
     */
    private $httpController;

    /**
     * @var AuthController
     */
    private $authController;

    /**
     * @var FileMapper
     */
    private $fileMapper;

    /**
     * @var UserMapper
     */
    private $userMapper;

    public function __construct(getID3 $getid3, HttpController $httpController, AuthController $authController, FileMapper $fileMapper, UserMapper $userMapper)
    {
        $this->getid3 = $getid3;
        $this->httpController = $httpController;
        $this->authController = $authController;
        $this->fileMapper = $fileMapper;
        $this->userMapper = $userMapper;
    }

    /**
     * Производит процедуру загрузки файла
     * @return bool
     * @throws \Exception
     */
    public function uploadAction(): bool
    {
        $file = $this->prepareToUpload($this->httpController->getFileFromGlobals(), $this->authController->getUserId());
        $fileId = $this->fileMapper->insert($file);

        if ($fileId) return true;
        return false;
    }

    /**
     * Подготавливает файл к загрузке
     * @param UploadedFile $file
     * @param string $userId
     * @return File
     * @throws \Exception
     */
    public function prepareToUpload(UploadedFile $file, string $userId): File
    {
        $userObject = $this->createUserIfNotExists($userId);
        $fileObject = $this->createFileFromGlobals($file, $userObject);

        return $fileObject;
    }

    /**
     * Создает пользователя, если его нет в базе данных, и записывает его туда
     * @param null $userId
     * @return User
     * @throws \Exception
     */
    private function createUserIfNotExists($userId = null): User
    {
        $user = null;

        if ($userId === null) {
            throw new \Exception('Ошибка записи в базу. Чтобы записаться, обновите страницу');
        }

        $user = $this->userMapper->findOne($userId, 'name');

        if ($user->getId() === null) {
            $user = $this->userMapper->create([
                'name' => $userId
            ]);

            $id = $this->userMapper->insert($user);
            $user->setId($id);
        }

        return $user;
    }

    /**
     * Создает файл типа File из глобального массива
     * @param UploadedFile $file
     * @param User $user
     * @return File
     * @throws \Exception
     */
    private function createFileFromGlobals(UploadedFile $file, User $user): File
    {
        $this->getid3->encoding = 'UTF-8';

        $fileInfo = $this->getid3->analyze($file->file);

        $fileName = $file->getClientFilename();

        $size = $fileInfo['filesize'];

        if (array_key_exists('video', $fileInfo)) {
            $res = $fileInfo['video']['resolution_x'] . 'x' . $fileInfo['video']['resolution_y'];
        } else {
            $res = NULL;
        }

        if (array_key_exists('playtime_string', $fileInfo)) {
            $dur = $fileInfo['playtime_string'];
        } else {
            $dur = NULL;
        }

        $pathToFile = $this->saveFileOnDisk($file, self::UPLOAD_DIR, $user->getName());

        preg_match('#(?<=id[a-z0-9]{13}/).*#', $pathToFile, $match);
        $localFilePath = $match[0];

        if ($this->isImage($file)) {
            $virtualFileName = pathinfo($pathToFile, PATHINFO_FILENAME) . '.' . pathinfo($pathToFile, PATHINFO_EXTENSION);
            $absolutePreviewDir = pathinfo($pathToFile, PATHINFO_DIRNAME);
            $absolutePreviewPath = $absolutePreviewDir . DIRECTORY_SEPARATOR . 'preview-' . $virtualFileName;

            $this->makeImgPreview($pathToFile, 'preview-' . $virtualFileName, $file->getClientMediaType(), $absolutePreviewDir);

            preg_match('#(?<=id[a-z0-9]{13}/).*#', $absolutePreviewPath, $match);
            $localPreviewPath = $match[0];
        } else {
            $localPreviewPath = NULL;
        }

        if (file_exists(ROOT . '/public/images/file-format-icons/' . strtolower(pathinfo($pathToFile, PATHINFO_EXTENSION)) . '.png')) {
            $ext = pathinfo($pathToFile, PATHINFO_EXTENSION);
        } else {
            $ext = NULL;
        }

        $fileObject = $this->fileMapper->create([
            'name' => $fileName,
            'size' => $size,
            'resolution' => $res,
            'duration' => $dur,
            'path' => $localFilePath,
            'preview_path' => $localPreviewPath,
            'ext' => $ext,
            'user' => $user->getId()
        ]);

        return $fileObject;
    }

    /**
     * Перемещает файл из временного хранилища в постоянное
     * @param UploadedFile $file
     * @param $root
     * @param $username
     * @return string
     * @throws \Exception
     */
    private function saveFileOnDisk(UploadedFile $file, $root, $username): string
    {
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);


        $subDir = date('Y-m-d');
        $filePath = $root . DIRECTORY_SEPARATOR . $username . DIRECTORY_SEPARATOR . $subDir;

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $file->moveTo($filePath . DIRECTORY_SEPARATOR . $filename);

        return $filePath . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Создает уменьшенную копию изображения
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

        $relativeWidth = 540;

        if ($originalWidth < $relativeWidth) {
            copy($img, $dest . DIRECTORY_SEPARATOR . $name);
            return true;
        }

        $coefficient = round($originalWidth / $relativeWidth, 2);

        $previewWidth = $originalWidth / $coefficient;
        $previewHeight = $originalHeight / $coefficient;

        $preview = imagecreatetruecolor($previewWidth, $previewHeight);
        imagefill($preview, 0, 0, 0xffffff);
        imagecopyresampled($preview, $originalSource, 0, 0, 0, 0, $previewWidth, $previewHeight, $originalWidth, $originalHeight);

        $saveImgFunc = 'image' . $format();

        if ($format() === 'jpeg') {
            $saveImgFunc($preview, $dest . '/' . $name, 70);
        } else if ($format() === 'png') {
            $saveImgFunc($preview, $dest . '/' . $name, 0);
        } else if ($format() === 'gif') {
            $saveImgFunc($preview, $dest . '/' . $name);
        }

        imagedestroy($originalSource);
        imagedestroy($preview);

        return true;
    }

    /**
     * Проверяет, является ли файл изображением
     * @param UploadedFile $file
     * @return bool
     */
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