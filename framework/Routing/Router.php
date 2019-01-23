<?php

namespace Framework\Routing;

use Framework\Dispatcher;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use UnderflowException;
use Zaine\Log;

class Router
{
    public const GET_METHOD = "GET";
    public const POST_METHOD = "POST";
    public const PUT_METHOD = "PUT";
    public const DELETE_METHOD = "DELETE";
    private static $routArray;

    private static function init()
    {
        if (self::$routArray == null) {
            self::$routArray = array();
        }
    }

    public static function addRout(Route $rout): void
    {
        if (self::$routArray == null) {
            self::init();
        }

        array_push(self::$routArray, $rout);
    }

    public static function goToCurrentRoute(Request $request): string
    {
        if (self::$routArray == null || empty(self::$routArray)) {
            return false;
        }

        foreach (self::$routArray as $rout) {
            if ($rout->isValid()) {
                return $rout->executeController($request);
            }
        }

        return Response::redirect('/');
    }
}
