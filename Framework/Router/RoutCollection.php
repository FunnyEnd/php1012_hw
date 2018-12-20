<?php

namespace Framework\Router;

use Framework\HTTP\Response;

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
                $html = $rout->executeController();
                Response::setContent($html);
                return true;
            }
        }
        return false;
    }
}