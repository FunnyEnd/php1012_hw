<?php

namespace Framework\FrameworkKernel;

use Framework\Router\Collection\RoutCollection;

class Kernel
{
    public function loadApplication($application)
    {
        $application->initConfig();
        $application->initRoutes();
        if (!RoutCollection::calCurrentRout()) {
            http_response_code(404);
        }
    }
}