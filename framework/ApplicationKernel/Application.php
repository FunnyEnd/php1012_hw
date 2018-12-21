<?php

namespace Framework\ApplicationKernel;

use Framework\Router\RoutCollection;
use UnderflowException;

class Application
{
    // todo: change to singleton

    private function initRoutes()
    {
        require 'src/routes.php';
    }

    private function initConfig()
    {
        foreach (glob("src/Config/*.config.php") as $filename) {
            include $filename;
        }
    }

    public function load()
    {
        $this->initConfig();
        $this->initRoutes();

        if (!RoutCollection::calCurrentRout()) {
            throw new UnderflowException("Rout don`t found.");
        }
    }
}