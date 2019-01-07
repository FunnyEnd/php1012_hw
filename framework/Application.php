<?php

namespace Framework;

use Framework\HTTP\Request;
use Framework\Routing\Router;
use UnderflowException;
use Zaine\Log;

class Application
{
    private const SRC_ROUTING_FILE = 'src/routes.php';
    private const SRC_SERVOCES_FILE = 'src/services.php';

    private $logger;
    private $dispatcher;

    public function __construct()
    {
        Config::init();
        $this->initRoutes();
        $this->dispatcher = new Dispatcher();
        $this->initServices();
        $this->logger = new Log("APP");

        $this->addInstance(Session::class, Session::getInstance());
    }

    public function addClass(string $class, array $params, bool $share = true)
    {
        $this->dispatcher->addClass($class, $params, $share);
    }

    public function addInstance(string $class, $obj, bool $share = true)
    {
        $this->dispatcher->addInstance($class, $obj, $share);
    }

    private function initRoutes()
    {
        require self::SRC_ROUTING_FILE;
    }

    private function initServices()
    {
        $services = require self::SRC_SERVOCES_FILE;
        foreach ($services as $service) {
            $this->addClass($service[0], $service[1]);
        }
    }

    public function execute(Request $request): string
    {
        try {
            return Router::goToCurrentRoute($request, $this->dispatcher);
        } catch (UnderflowException $ue) {
            $this->logger->error($ue->getMessage());
        }
        return "";
    }
}