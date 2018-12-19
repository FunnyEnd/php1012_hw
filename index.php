<?php
spl_autoload_register(function ($class) {
  $includeClassName = str_replace('\\', '/', $class . ".php");
  if (file_exists($includeClassName))
    require $includeClassName;

});

set_error_handler(
    function ($errorType, $errorText, $errfile, $errline) {
      $errorTime = date("H:i:m");
      $backtrace = debug_backtrace();
      $backtraceStr = "";
      foreach ($backtrace as $b) {
        if ($b['function'] == '{closure}')
          continue;

        $backtraceStr .= $b['file'] . " at line " . $b['line'] . ". Call function " . $b['function'] . "(); \n";
      }
      $res = "[$errorTime] {$errorType}. $errorText $errfile at line $errline \n$backtraceStr \n";
      file_put_contents('error-log.txt', $res, FILE_APPEND);
    });

set_exception_handler(function (Throwable $e) {
  $errorTime = date("H:i:m");
  $backtrace = $e->getTraceAsString();
  $res = "[$errorTime] Uncaught exception. " . $e->getMessage() . $backtrace . "\n";
  file_put_contents('error-log.txt', $res, FILE_APPEND);
});

$application = new \Framework\ApplicationKernel\Application();
$application->load();
exit(0);