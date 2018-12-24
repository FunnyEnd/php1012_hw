<?php

/* Framework loader*/
spl_autoload_register(function (string $class) {

    preg_match("/^(([A-Z]{1}[a-z0-9]*)+\\\{1})+([A-Z]{1}[a-z0-9]*)+$/", $class, $match);
    if( count($match) == 0){
        return;
    }

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
