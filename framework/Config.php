<?php

namespace Framework;

class Config
{
    protected static $configArray = null;

    public static function init(string $path = null)
    {
        self::$configArray = [];

        if ($path !== null) {
            $path .= "*.config.php";
        } else {
            $path = "../src/Config/*.config.php";
        }

        foreach (glob($path) as $filename) {
            $arr = include $filename;
            self::$configArray = array_merge(self::$configArray, $arr);
        }
    }

    public static function get(string $param): string
    {
        if (self::exist($param)) {
            return self::$configArray[$param];
        } else {
            throw new \InvalidArgumentException('Invalid config argument ' . $param);
        }
    }

    public static function set(string $param, string $value): void
    {
        if (self::$configArray === null) {
            self::$configArray = [];
        }

        self::$configArray[$param] = $value;
    }

    public static function exist(string $param): bool
    {
        if (self::$configArray === null) {
            return false;
        }

        return isset(self::$configArray[$param]);
    }
}
