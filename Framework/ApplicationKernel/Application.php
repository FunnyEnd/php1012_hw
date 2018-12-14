<?php

namespace Framework\ApplicationKernel;

use Framework\Router\Collection\RoutCollection;

class Application
{
    // todo: change to singleton

    private function initRoutes()
    {
        require 'App/routes.php';
    }

    private function initConfig()
    {
        foreach (glob("App/Config/*.config.php") as $filename) {
            include $filename;
        }
    }

    public function load(){
        $this->initConfig();
        $this->initRoutes();

        if (!RoutCollection::calCurrentRout()) {
            http_response_code(404);
        }
    }
}