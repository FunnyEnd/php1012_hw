<?php

namespace App;

use Framework\Router\RoutCollection;
use Framework\Router\Rout;

RoutCollection::addRout(new Rout(
        "get",
        "/",
        'App\Controller\HomeController::showHome'
));

RoutCollection::addRout(new Rout(
        "get",
        "/product/#id",
        "App\Controller\ProductController::showProduct"
));

RoutCollection::addRout(new Rout(
        "get",
        "/category/#id",
        "App\Controller\CategoryController::showCategory"
));