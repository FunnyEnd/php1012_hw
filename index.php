<?php

const SITE_ROOT = "http://localhost/php1012_hw/";
$products = include('list_of_products.php');
$category = include('list_of_category.php');

$page = isset($_GET['page']) ? $_GET['page'] : '';

switch ($page) {
    case 'product' :
        {
            $k = array_search(intval($_GET['id']), array_column($products, 'id'));
            $cur_prod = $products[$k];
            $cat_index = array_search($cur_prod['cat'], array_column($category, 'id'));
            if($cat_index === false || $k === false){
                include "templates/home.php";
                break;
            }
            $cur_cat = $category[$cat_index];
            include "templates/product_detailed.php";
            break;
        }
    case 'cat':
        {
            $cur_cat_id = intval($_GET['id']);
            $cat_index = array_search($cur_cat_id, array_column($category, 'id'));
            if($cat_index === false){
                include "templates/home.php";
                break;
            }
            $cur_cat = $category[$cat_index];

            include "templates/product_list_at_category.php";

            break;
        }
    default:
        {
            include "templates/home.php";
            break;
        }
}
