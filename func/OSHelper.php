<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 05.03.2018
 * Time: 17:30
 */

namespace app\func;


/**
 * Хэлпер для операционных систем
 *
 * Class OSHelper
 * @package app\func
 */
class OSHelper
{
    protected static $os = [
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    ];

    /**
     * Установить имя файла в зависимости от ОС
     *
     * @param string $filename
     * @return string
     */
    public static function setFileNameByOS($filename)
    {
        if (DIRECTORY_SEPARATOR === "\\" && !self::isWindows10()) {
            return mb_convert_encoding($filename, 'Windows-1251', 'UTF-8');
        }
        return $filename;
    }

    /**
     * Вывести имя файла в зависимости от ОС
     *
     * @param string $filename
     * @return string
     */
    public static function getFileNameByOS($filename)
    {
        if (DIRECTORY_SEPARATOR === "\\" && !self::isWindows10()) {
            return mb_convert_encoding($filename, 'UTF-8', 'Windows-1251');
        }
        return $filename;
    }

    public static function isWindows10()
    {
        return isset($_SERVER['HTTP_USER_AGENT'])
            ? preg_match('/windows nt 10/i', $_SERVER['HTTP_USER_AGENT'])
            : preg_match('/windows 10/i', php_uname());
    }

}