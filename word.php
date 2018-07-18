<?php

/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 18-7-18
 * Time: 上午3:27
 */
class Word
{
    public static $data = [];
    public static $line = 0;

    public static function init()
    {
        $file = __DIR__ . '/21w.txt';
        $spl_object = new SplFileObject($file, 'rb');
        while (!$spl_object->eof()) {
            self::$data[] = trim($spl_object->fgets());
        }
        self::$line = count(self::$data) - 1;
    }

    public static function create(int $wordNum = 5)
    {
        if (self::$line == 0) {
            self::init();
        }
        $string = '';
        for ($i = 0; $i < $wordNum; $i++) {
            $string .= self::$data[mt_rand(0, self::$line)];
        }
        return $string;
    }
}




