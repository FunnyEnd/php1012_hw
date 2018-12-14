<?php

namespace App\Controller;

use App\Models\Product;
use App\Models\Category;

class CategoryController
{
    private $product;
    private $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function showCategory(array $getRequest): string
    {
        $id = $getRequest['id'];
        $cur_cat = $this->category->getById($id);
        $cur_cat_products = $this->product->getByCatId($id);
        $category = $this->category->getAll();
        return include \App\Config\TEMPLATE_FOLDER . "product_list_at_category.php";
    }
}