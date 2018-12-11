<?php
const SITE_ROOT = "http://localhost/php1012_hw/";
const TEMPLATE_FOLDER = "templates/";

$products = include('list_of_products.php');
$category = include('list_of_category.php');

spl_autoload_register(function ($class) {
    include "Classes/" . $class . ".php";
});

$productRepository = new ProductRepository($products);
$categoryRepository = new CategoryRepository($category);

$page = isset($_GET['page']) ? $_GET['page'] : '';

switch ($page) {
    case 'product' :
        {
            $id = $_GET['id'];
            $cur_prod = $productRepository->getById($id);
            $cur_cat = $categoryRepository->getById($cur_prod['cat']);
            include TEMPLATE_FOLDER . "product_detailed.php";
            break;
        }
    case 'cat':
        {
            $cur_cat_id = intval($_GET['id']);
            $cur_cat = $categoryRepository->getById($cur_cat_id);
            $cur_cat_products = $productRepository->getByCatId($cur_cat_id);
            include TEMPLATE_FOLDER . "product_list_at_category.php";
            break;
        }
    default:
        {
            $cat_1_products = $productRepository->getByCatId(1);
            $cat_2_products = $productRepository->getByCatId(2);
            $cat_3_products = $productRepository->getByCatId(3);
            include TEMPLATE_FOLDER . "home.php";
            break;
        }
}
