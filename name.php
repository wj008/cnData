<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 18-7-18
 * Time: 上午3:32
 */

class Name
{
    public static $data = [];
    public static $line = 0;

    public static function init()
    {
        $file = __DIR__ . '/120WName.txt';
        $spl_object = new SplFileObject($file, 'rb');
        while (!$spl_object->eof()) {
            self::$data[] = trim($spl_object->fgets());
        }
        self::$line = count(self::$data) - 1;
    }

    public static function create()
    {
        if (self::$line == 0) {
            self::init();
        }
        return self::$data[mt_rand(0, self::$line)];
    }

    public static function getUserName($len = 5)
    {
        $chars = 'ABCDEFGHIJKLNMOPQRSTUVWXYZabcdefghijklnmopqrstuvwxyz0123456789';
        $max = strlen($chars) - 1;
        $string = '';
        for ($i = 0; $i < $len; $i++) {
            $string .= $chars[mt_rand(0, $max)];
        }
        return $string;
    }
}