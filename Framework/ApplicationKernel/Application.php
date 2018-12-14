<?php

namespace Framework\ApplicationKernel;

class Application
{
    // todo: change to singleton

    public function initRoutes()
    {
        require 'App/routes.php';
    }

    public function initConfig()
    {
        foreach (glob("App/Config/*.config.php") as $filename) {
            include $filename;
        }
    }
}