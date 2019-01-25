<?php

namespace Framework;

use ReflectionClass;
use ReflectionException;
use Zaine\Log;

class Dispatcher
{
    private static $services = [];

    private static $instantiated = [];

    private static $shared = [];

    public static function addInstance(string $class, $service, bool $share = true)
    {
        self::$services[$class] = $service;
        self::$instantiated[$class] = $service;
        self::$shared[$class] = $share;
    }

    public static function addClass(string $class, array $params, bool $share = true)
    {
        self::$services[$class] = $params;
        self::$shared[$class] = $share;
    }

    public static function has(string $interface): bool
    {
        return isset(self::$services[$interface]) || isset(self::$instantiated[$interface]);
    }

    public static function get(string $class)
    {
        if (isset(self::$instantiated[$class]) && self::$shared[$class]) {
            return self::$instantiated[$class];
        }

        if (!isset(self::$services[$class])) {
            return null;
        }

        try {
            $args = self::$services[$class];
            $object = (new ReflectionClass($class))->newInstanceArgs($args);

            if (self::$shared[$class]) {
                self::$instantiated[$class] = $object;
            }

            return $object;
        } catch (ReflectionException $e) {
            $logger = new Log('Dispatcher');
            $logger->error($e->getMessage());
        }

        return null;
    }
}
