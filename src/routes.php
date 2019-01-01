<?php

namespace App;

use Framework\Routing\Router;
use Framework\Routing\Route;

// :d - digit
Router::addRout(new Route(
        "get",
        "/",
        'App\Controller\HomeController::showHome'
));

Router::addRout(new Route(
        "get",
        "/product/:d",
        "App\Controller\ProductController::showProduct",
        ['id']
));

Router::addRout(new Route(
        "get",
        "/category/:d",
        "App\Controller\CategoryController::showCategory",
        ['id']
));