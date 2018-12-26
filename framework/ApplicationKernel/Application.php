<?php

namespace Framework\ApplicationKernel;

use Framework\Config;
use Framework\Logger\FileLogger;
use Framework\Logger\Log;
use Framework\Router\RoutCollection;
use UnderflowException;

class Application
{
    private $logger;

    public function __construct()
    {
        $this->initRoutes();

        $fileLogger = new FileLogger();
        $this->logger = new Log($fileLogger);

        Config::init();
    }

    private function initRoutes()
    {
        require 'src/routes.php';
    }

    public function execute($request): string
    {
        try {
            return RoutCollection::calCurrentRout($request);
        } catch (UnderflowException $ue) {
            $this->logger->error($ue->getMessage());
        }
    }
}