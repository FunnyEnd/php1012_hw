<?php

namespace App;

use Framework\Router\RoutCollection;
use Framework\Router\Rout;

// :d - digit
RoutCollection::addRout(new Rout(
        "get",
        "/",
        'App\Controller\HomeController::showHome'
));

RoutCollection::addRout(new Rout(
        "get",
        "/product/:d",
        "App\Controller\ProductController::showProduct",
        ['id']
));

RoutCollection::addRout(new Rout(
        "get",
        "/category/:d",
        "App\Controller\CategoryController::showCategory",
        ['id']
));