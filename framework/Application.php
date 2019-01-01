<?php

namespace Framework;

use Framework\Routing\Router;
use UnderflowException;
use Zaine\Log;

class Application
{
    private const SRC_ROUTING_FILE = 'src/routes.php';
    private $logger;

    public function __construct()
    {
        $this->initRoutes();
        $this->logger = new Log("APP");
        Config::init();
    }

    private function initRoutes()
    {
        require self::SRC_ROUTING_FILE;
    }

    public function execute($request): string
    {
        try {
            return Router::goToCurrentRoute($request);
        } catch (UnderflowException $ue) {
            $this->logger->error($ue->getMessage());
        }
    }
}