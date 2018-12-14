<?php
spl_autoload_register(function ($class) {
    $includeClassName = str_replace('\\', '/', $class . ".php");
    if (file_exists($includeClassName))
        require $includeClassName;

});

$application = new \Framework\ApplicationKernel\Application();

$kernel = new \Framework\FrameworkKernel\Kernel();

$kernel->loadApplication($application);