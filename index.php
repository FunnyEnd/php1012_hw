<?php

namespace App;

const SITE_ROOT = "http://mysite.loc/";
const TEMPLATE_FOLDER = "Templates/";

spl_autoload_register(function ($class) {
    $includeClassName = str_replace('\\', '/', $class . ".php");
    require $includeClassName;
});

use App\Collection\RoutCollection;
use App\Models\Rout;

$routCollection = new RoutCollection();

$routCollection->addRout(new Rout(
    "/",
    'App\Controller\HomeController::showHome'
));

$routCollection->addRout(new Rout(
    "/product/#id",
    'App\Controller\ProductController::showProduct'
));

$routCollection->addRout(new Rout(
    "/category/#id",
    'App\Controller\CategoryController::showCategory'
));

if (!$routCollection->calCurrentRout()) {
    http_response_code(404);
//    $requestURI = $_SERVER['REQUEST_URI'];
//    echo "Page '$requestURI' not found!";
}