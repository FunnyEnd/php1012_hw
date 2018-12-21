<?php
// TODO: camelCase
spl_autoload_register(function (string $class) {
    $appPrefix = "App\\";
    $baseDir = BASE_PATH . "/src/";

    if (strncmp($appPrefix, $class, strlen($appPrefix)) !== 0) {
        return;
    }

    $classPath = substr($class, strlen($appPrefix), strlen($class));
    $fullClassPath = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $classPath) . ".php";

    if (file_exists($fullClassPath)) {
        require_once $fullClassPath;
    }
});