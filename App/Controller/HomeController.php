<?php

namespace App\Controller;

use App\Models\Product;
use App\Models\Category;

class HomeController
{
    private $product;
    private $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function showHome()
    {
        $category = $this->category->getAll();
        $cat_1_products = $this->product->getByCatId(1);
        $cat_2_products = $this->product->getByCatId(2);
        $cat_3_products = $this->product->getByCatId(3);

        return include \App\TEMPLATE_FOLDER . "home.php";
    }
}