<?php

namespace Framework\Routing;

use Framework\Dispatcher;
use Framework\HTTP\Request;
use UnderflowException;

class Router
{
    private static $routArray;

    private static function init()
    {
        if (self::$routArray == null) {
            self::$routArray = array();
        }
    }

    public static function addRout(Route $rout): void
    {
        if (self::$routArray == null)
            self::init();

        array_push(self::$routArray, $rout);
    }

    public static function goToCurrentRoute(Request $request, Dispatcher $dispatcher): string
    {
        if (self::$routArray == null) {
            return false;
        }
        $success = false;
        $html = "";
        foreach (self::$routArray as $rout) {
            if ($rout->isValid()) {
                $html = $rout->executeController($request, $dispatcher);
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