<?php

namespace JSYF\App\Controllers;

/**
 * Класс, отвечающий за работу с пользователями
 */
class UserController
{
    /**
     * @var string Путь к аватаркам в системе
     */
    private $avatarsPath = ROOT . '/public/images/userpics';

    /**
     * @var array Теплые цвета
     */
    private $warmColors = [
        ['red' => 227, 'green' => 38, 'blue' => 54],
        ['red' => 255, 'green' => 36, 'blue' => 0],
        ['red' => 171, 'green' => 39, 'blue' => 79],
        ['red' => 241, 'green' => 156, 'blue' => 187],
        ['red' => 159, 'green' => 43, 'blue' => 104],
        ['red' => 237, 'green' => 60, 'blue' => 202],
        ['red' => 153, 'green' => 102, 'blue' => 204],
        ['red' => 106, 'green' => 90, 'blue' => 205],
        ['red' => 153, 'green' => 0, 'blue' => 102],
        ['red' => 255, 'green' => 219, 'blue' => 139],
        ['red' => 206, 'green' => 210, 'blue' => 58],
        ['red' => 255, 'green' => 184, 'blue' => 65],
        ['red' => 255, 'green' => 151, 'blue' => 187],
        ['red' => 255, 'green' => 176, 'blue' => 46],
        ['red' => 145, 'green' => 30, 'blue' => 66],
        ['red' => 223, 'green' => 115, 'blue' => 255],
        ['red' => 255, 'green' => 207, 'blue' => 72],
        ['red' => 246, 'green' => 74, 'blue' => 70],
        ['red' => 254, 'green' => 40, 'blue' => 162],
        ['red' => 248, 'green' => 23, 'blue' => 62],
        ['red' => 187, 'green' => 108, 'blue' => 138],
        ['red' => 184, 'green' => 93, 'blue' => 67],
        ['red' => 237, 'green' => 60, 'blue' => 202],
        ['red' => 204, 'green' => 78, 'blue' => 292],
        ['red' => 245, 'green' => 64, 'blue' => 33],
        ['red' => 247, 'green' => 148, 'blue' => 60],
        ['red' => 199, 'green' => 21, 'blue' => 133],
        ['red' => 227, 'green' => 37, 'blue' => 107],
        ['red' => 255, 'green' => 204, 'blue' => 0],
    ];

    /**
     * @var array Холодные цвета
     */
    private $coolColors = [
        ['red' => 127, 'green' => 255, 'blue' => 212],
        ['red' => 68, 'green' => 148, 'blue' => 74],
        ['red' => 168, 'green' => 228, 'blue' => 160],
        ['red' => 119, 'green' => 221, 'blue' => 231],
        ['red' => 30, 'green' => 89, 'blue' => 60],
        ['red' => 63, 'green' => 136, 'blue' => 143],
        ['red' => 48, 'green' => 213, 'blue' => 200],
        ['red' => 171, 'green' => 205, 'blue' => 239],
        ['red' => 152, 'green' => 251, 'blue' => 152],
        ['red' => 140, 'green' => 203, 'blue' => 94],
        ['red' => 42, 'green' => 141, 'blue' => 156],
        ['red' => 71, 'green' => 167, 'blue' => 106],
        ['red' => 0, 'green' => 155, 'blue' => 118],
        ['red' => 62, 'green' => 95, 'blue' => 138],
        ['red' => 100, 'green' => 149, 'blue' => 237],
        ['red' => 0, 'green' => 255, 'blue' => 127],
        ['red' => 167, 'green' => 252, 'blue' => 0],
        ['red' => 37, 'green' => 109, 'blue' => 123],
        ['red' => 0, 'green' => 149, 'blue' => 182],
        ['red' => 48, 'green' => 186, 'blue' => 143],
        ['red' => 135, 'green' => 206, 'blue' => 235],
        ['red' => 0, 'green' => 105, 'blue' => 62],
        ['red' => 21, 'green' => 96, 'blue' => 189],
        ['red' => 30, 'green' => 144, 'blue' => 255],
        ['red' => 1, 'green' => 121, 'blue' => 111],
        ['red' => 46, 'green' => 139, 'blue' => 87],
        ['red' => 41, 'green' => 171, 'blue' => 135],
        ['red' => 95, 'green' => 158, 'blue' => 160],
        ['red' => 0, 'green' => 103, 'blue' => 126],
        ['red' => 127, 'green' => 199, 'blue' => 255]
    ];

    /**
     * Генерирует аватарку пользователя
     * @param string $userId
     * @return void
     */
    public function generateAvatar(string $userId): void
    {
        $image = imagecreatefrompng("$this->avatarsPath/userpic-template.png");

        $srcW = imagesx($image);
        $srcH = imagesy($image);
        $rgb = [255, 255, 255];
        $rgb1 = [255, 0, 0];
        $replaceRGB = $this->coolColors[array_rand($this->coolColors)];
        $replaceRGB1 = $this->warmColors[array_rand($this->warmColors)];
        imagealphablending($image, false);
        imagesavealpha($image, true);

        for($x = 0; $x < $srcW; $x++) {
            for($y = 0; $y < $srcH; $y++) {
                $colors = imagecolorsforindex($image, imagecolorat($image, $x, $y));
            
                if ($colors['red'] === $rgb[0] && $colors['green'] === $rgb[1] && $colors['blue'] === $rgb[2]) {
                    $colors = imagecolorallocatealpha(
                        $image, $replaceRGB['red'], $replaceRGB['green'], $replaceRGB['blue'], $colors['alpha']
                    );
                    imagesetpixel($image, $x, $y, $colors);

                } else if ($colors['red'] === $rgb1[0] && $colors['green'] === $rgb1[1] && $colors['blue'] === $rgb1[2]) {
                    $colors = imagecolorallocatealpha(
                        $image, $replaceRGB1['red'], $replaceRGB1['green'], $replaceRGB1['blue'], $colors['alpha']
                    );
                    imagesetpixel($image, $x, $y, $colors);
                }
            }
        }

        imagepng($image, "$this->avatarsPath/$userId.png");
    }
}