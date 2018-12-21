<?php
// TODO: camelCase
/* Framework loader*/
spl_autoload_register(function (string $class) {
    $appPrefix = "Framework\\";
    $baseDir = BASE_PATH . "/framework/";

    if (strncmp($appPrefix, $class, strlen($appPrefix)) !== 0) {
        return;
    }

    $classPath = substr($class, strlen($appPrefix), strlen($class));
    $fullClassPath = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $classPath) . ".php";

    if (file_exists($fullClassPath)) {
        require_once $fullClassPath;
    }
});

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
