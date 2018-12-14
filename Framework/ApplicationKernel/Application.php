<?php

namespace Framework\ApplicationKernel;

class Application
{
    public function initRoutes()
    {
        require 'App/routes.php';
    }

    public function initConfig()
    {
        include 'App/Config/templates.config.php';
    }
}