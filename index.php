<?php

require_once __DIR__ . '/vendor/autoload.php';

$request = \Framework\HTTP\Request::getInstance();
$application = new \Framework\Application();
\Framework\HTTP\Response::setContent($application->execute($request));
\Framework\HTTP\Response::send();
