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
    private const SRC_SERVICES_PHP = 'src/services.php';

    private $logger;

    public function __construct()
    {
        $this->intiErrorHandler();
        $this->initExceptionHandler();
        Config::init();
        $this->initRoutes();
        $this->initServices();
        $this->logger = new Log("APP");

        Dispatcher::addInstance(Session::class, Session::getInstance());
        Dispatcher::addInstance(Request::class, Request::getInstance());
        Dispatcher::addClass(Log::class,['Logger']);
    }

    private function initRoutes()
    {
        require self::SRC_ROUTING_FILE;
    }

    private function initServices()
    {
        $services = require self::SRC_SERVICES_PHP;
        foreach ($services as $service) {
            Dispatcher::addClass($service[0], $service[1]);
        }
    }

    private function intiErrorHandler(): void
    {
        set_error_handler(function ($errorType, $errorText, $errfile, $errline) {
            $errorTime = date("H:i:m");
            $backtrace = debug_backtrace();
            ob_start();
            var_dump($backtrace);
            $backtraceStr = ob_get_clean();
            $res = "[$errorTime] {$errorType}. $errorText $errfile at line $errline \n$backtraceStr \n";

            $log = new Log("Error handler");
            $log->error($res);
        });
    }

    public function execute(Request $request): string
    {
        try {
            $time_start = microtime(true);

            $content = Router::goToCurrentRoute($request);

            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            $uri = $_SERVER['REQUEST_URI'];
            $this->logger->emergency("Total Execution Time: {$execution_time}. URI: {$uri}.\n");

            return $content;
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
            $log = new Log("Exception handler");
            $log->error($res);
        });
    }
}