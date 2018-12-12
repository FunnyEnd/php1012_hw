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

    public function showProduct($id){
        $cur_prod = $this->product->getById($id);
        $cur_cat = $this->product->getById($cur_prod['cat']);
        $category = $this->category->getAll();
        return include \App\TEMPLATE_FOLDER . "product_detailed.php";
//        return "";
    }
}