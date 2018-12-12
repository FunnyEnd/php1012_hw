<?php

namespace App;

set_include_path("/home/NIX/phpstudent/www/php1012_hw");

const SITE_ROOT = "http://localhost/php1012_hw/";
const TEMPLATE_FOLDER = "templates/";

spl_autoload_register(function ($class) {
    $includeClassName = str_replace('\\', '/', $class . ".php");
    include $includeClassName;
});


use App\Controller\HomeController;
use App\Controller\ProductController;
use App\Controller\CategoryController;

$page = isset($_GET['page']) ? $_GET['page'] : '';
switch ($page) {
    case 'product' :
        {
            $id = intval($_GET['id']);
            $productController = new ProductController();
            echo $productController->showProduct($id);
            break;
        }
    case 'cat':
        {
            $id = intval($_GET['id']);
            $categoryController = new CategoryController();
            echo $categoryController->showCategory($id);
            break;
        }
    case 'home':{
        $homeController = new HomeController();
        echo $homeController->showHome();
        break;
    }
    default :
        {
            http_response_code(404);
            $requestURI = $_SERVER['REQUEST_URI'];
            echo "Page '$requestURI' not found!";
        }
}
