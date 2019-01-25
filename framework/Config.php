<?php

namespace Framework;

class Config
{
    protected static $configArray = null;

    public static function init()
    {
        self::$configArray = [];
        foreach (glob("src/Config/*.config.php") as $filename) {
            $arr = include $filename;
            self::$configArray = array_merge(self::$configArray, $arr);
        }
    }

    public static function get(string $param): string
    {
        if (self::exist($param)) {
            return self::$configArray[$param];
        } else {
            throw new \InvalidArgumentException('Invalid config argument');
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
