<?php

namespace Framework;

use Framework\HTTP\Request;
use Framework\Routing\Router;
use Throwable;
use UnderflowException;
use Zaine\Log;

class Application
{
    private const SRC_ROUTING_FILE = 'src/routes.php';
    private const SRC_SERVOCES_FILE = 'src/services.php';

    private $logger;
    private $dispatcher;

    public function __construct()
    {
        $this->intiErrorHandler();
        $this->initExceptionHandler();
        Config::init();
        $this->initRoutes();
        $this->dispatcher = new Dispatcher();
        $this->initServices();
        $this->logger = new Log("APP");

        $this->addInstance(Session::class, Session::getInstance());
    }

    public function addClass(string $class, array $params, bool $share = true)
    {
        $this->dispatcher->addClass($class, $params, $share);
    }

    public function addInstance(string $class, $obj, bool $share = true)
    {
        $this->dispatcher->addInstance($class, $obj, $share);
    }

    private function initRoutes()
    {
        require self::SRC_ROUTING_FILE;
    }

    private function initServices()
    {
        $services = require self::SRC_SERVOCES_FILE;
        foreach ($services as $service) {
            $this->addClass($service[0], $service[1]);
        }
    }

    private function intiErrorHandler():void{
        set_error_handler(function ($errorType, $errorText, $errfile, $errline) {
            $errorTime = date("H:i:m");
            $backtrace = debug_backtrace();
            $backtraceStr = "";
            foreach ($backtrace as $b) {
                if ($b['function'] == '{closure}')
                    continue;

                $backtraceStr .= $b['file'] . ". Call function " . $b['function'] . "(); \n";
            }
            $res = "[$errorTime] {$errorType}. $errorText $errfile at line $errline \n$backtraceStr \n";

            $log = new \Zaine\Log("Error handler");
            $log->error($res);
        });
    }

    public function execute(Request $request): string
    {
        try {
            return Router::goToCurrentRoute($request, $this->dispatcher);
        } catch (UnderflowException $ue) {
            $this->logger->error($ue->getMessage());
        }
        return "";
    }

    private function initExceptionHandler()
    {
        set_exception_handler(function (Throwable $e) {
            $errorTime = date("H:i:m");
            $backtrace = $e->getTraceAsString();
            $res = "[$errorTime] Uncaught exception. " . $e->getMessage() . $backtrace . "\n";
            $log = new \Zaine\Log("Exception handler");
            $log->error($res);
        });
    }
}