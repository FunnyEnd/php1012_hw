<?php
namespace App;

use Framework\Router\Collection\RoutCollection;
use Framework\Router\Models\Rout;

RoutCollection::addRout(new Rout(
    "get",
    "/",
    'App\Controller\HomeController::showHome'
));

RoutCollection::addRout(new Rout(
    "get",
    "/product/#id",
    "App\Controller\ProductController::showProduct",
    "ProductShowRequest"
));

RoutCollection::addRout(new Rout(
    "get",
    "/category/#id",
    "App\Controller\CategoryController::showCategory",
    "CategoryShowRequest"
));