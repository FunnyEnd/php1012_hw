<?php

namespace Framework\Router\Collection;

use Framework\Router\Models\Rout;

class RoutCollection
{
    private static $routArray;

    private static function init()
    {
        if (self::$routArray == null) {
            self::$routArray = array();
        }
    }

    public static function addRout(Rout $rout): void
    {
        if (self::$routArray == null)
            self::init();

        array_push(self::$routArray, $rout);
    }

    public static function calCurrentRout(): bool
    {
        if (self::$routArray == null) {
            return false;
        }

        foreach (self::$routArray as $rout) {
            if ($rout->isEqCurRequest()) {
                $rout->executeController();
                return true;
            }
        }
        return false;
    }
}