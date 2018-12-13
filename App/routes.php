<?php

use App\Collection\RoutCollection;
use App\Models\Rout;

RoutCollection::addRout(new Rout(
    "/",
    'App\Controller\HomeController::showHome'
));

RoutCollection::addRout(new Rout(
    "/product/#id",
    'App\Controller\ProductController::showProduct'
));

RoutCollection::addRout(new Rout(
    "/category/#id",
    'App\Controller\CategoryController::showCategory'
));