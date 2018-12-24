<?php

//define('BASE_PATH', __DIR__);

require_once __DIR__ . '/vendor/autoload.php';

$log = new \Zaine\Log("test");
$log->error("some error");

set_exception_handler(function (Throwable $e) {
    $errorTime = date("H:i:m");
    $backtrace = $e->getTraceAsString();
    $res = "[$errorTime] Uncaught exception. " . $e->getMessage() . $backtrace . "\n";
    $fileLogger = new \Framework\Logger\FileLogger();
    $log = new \Framework\Logger\Log($fileLogger);
    $log->error($res);
});

set_error_handler(function ($errorType, $errorText, $errfile, $errline) {
    $errorTime = date("H:i:m");
    $backtrace = debug_backtrace();
    $backtraceStr = "";
    foreach ($backtrace as $b) {
        if ($b['function'] == '{closure}')
            continue;

        $backtraceStr .= $b['file'] . " at line " . $b['line'] . ". Call function " . $b['function'] . "(); \n";
    }
    $res = "[$errorTime] {$errorType}. $errorText $errfile at line $errline \n$backtraceStr \n";

    $fileLogger = new \Framework\Logger\FileLogger();
    $log = new \Framework\Logger\Log($fileLogger);
    $log->error($res);
});


$application = new \Framework\ApplicationKernel\Application();
\Framework\HTTP\Response::setContent($application->execute());
\Framework\HTTP\Response::send();