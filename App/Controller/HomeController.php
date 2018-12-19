<?php

namespace App\Controller;

use App\Models\Product;
use App\Models\Category;
use Framework\TemplateEngine\View;

class HomeController
{
    private $product;
    private $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function showHome(): string
    {

        $category = $this->category->getAll();
        $cat_1_products = $this->product->getByCatId(1);
        $cat_2_products = $this->product->getByCatId(2);
        $cat_3_products = $this->product->getByCatId(3);

        return View::render('home', array(
                'category' => $category,
                'cat_1_products' => $cat_1_products,
                'cat_2_products' => $cat_2_products,
                'cat_3_products' => $cat_3_products
        ));
    }
}