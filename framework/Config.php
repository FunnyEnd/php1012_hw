<?php

namespace Framework;


class Config
{
    private static $configArray;

    public static function init()
    {
        self::$configArray = array();
        foreach (glob("src/Config/*.config.php") as $filename) {
            $arr = include $filename;
            self::$configArray = array_merge(self::$configArray, $arr);
        }
    }

    public static function get(string $param): string
    {
        if(array_key_exists($param, self::$configArray))
            return self::$configArray[$param];
        else
            throw new \InvalidArgumentException('invalid config argument');
    }
}