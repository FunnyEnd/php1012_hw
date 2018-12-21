<?php

define('BASE_PATH', __DIR__);

/* Framework load*/
include "framework/autoload.php";

/* App load*/
include "src/autoload.php";

$application = new \Framework\ApplicationKernel\Application();
\Framework\HTTP\Response::setContent($application->execute());
\Framework\HTTP\Response::send();