<?php

namespace App\Controller;

use App\Models\Product;
use App\Models\Category;

class ProductController
{
    private $product;
    private $category;

    function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function showProduct(array $getRequest): string
    {
        $id = $getRequest['id'];
        $cur_prod = $this->product->getById($id);
        $cur_cat = $this->category->getById($cur_prod['cat']);
        $category = $this->category->getAll();
        return include \App\Config\TEMPLATE_FOLDER . "product_detailed.php";
    }
}