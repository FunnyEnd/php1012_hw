<?php

namespace Framework\Router;

use UnderflowException;

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

    public static function calCurrentRout(): string
    {
        if (self::$routArray == null) {
            return false;
        }

        $success = false;
        $html = "";
        foreach (self::$routArray as $rout) {
            if ($rout->isEqCurRequest()) {
                $html = $rout->executeController();
                $success = true;
            }
        }

        if($success){
            return $html;
        } else {
            throw new UnderflowException("Rout don`t found.");
        }
    }
}