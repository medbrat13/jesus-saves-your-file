<?php


namespace JSYF\App\Controllers;

use getID3;
use JSYF\Kernel\Base\Controller;
use JSYF\Kernel\DB\Connection;
use Slim\Http\UploadedFile;

/**
 * Контроллер главной страницы
 */
class UploadController extends Controller
{
    /**
     * Каталог для загрузки файлов
     */
    private const UPLOAD_DIR = ROOT . '/files';

    /**
     * @var getID3
     */
    private $getid3;

    public function __construct(getID3 $getid3)
    {
        parent::__construct();

        $this->getid3 = $getid3;
    }

    public function uploadAction(UploadedFile $file)
    {
        $movedFile = $this->moveUploadedFile( $file, self::UPLOAD_DIR, 'ketovnik',);

        $this->connection->getPdo();

        $this->getid3->encoding = 'UTF-8';
        $this->getid3->analyze($movedFile);

        $originalFileName = $file->getClientFilename();
        $virtualFileName = $this->getid3->info['filename'];
        $albumName = '';
        $filesize = $this->getid3->info['filesize'];
        $fileDate = 'сегодня';


        return [
            'name' => $originalFileName,
            'size' => $filesize,
            'date' => $fileDate
            ];


    }

    private function moveUploadedFile(UploadedFile $file, $root, $username, $album = 'default'): string
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

        $file->moveTo($albumPath . DIRECTORY_SEPARATOR . $filename);

        return $albumPath . DIRECTORY_SEPARATOR . $filename;
    }
}