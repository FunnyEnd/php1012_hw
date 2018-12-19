<?php

namespace App\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Request\ProductShowRequest;
use Framework\HTTP\Response;
use Framework\TemplateEngine\View;

class ProductController
{
    private $product;
    private $category;

    function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function showProduct(ProductShowRequest $request): string
    {
        $id = $request->get("id");
        $cur_prod = $this->product->getById($id);

        if(empty($cur_prod)){
            Response::setResponseCode(404);
            // todo: page product don`t exist
            return View::render('404');
        }

        $cur_cat = $this->category->getById($cur_prod['cat']);

        if(empty($cur_cat)){
            Response::setResponseCode(404);
            // todo: page category don`t exist
            return View::render('404');
        }

        $category = $this->category->getAll();

        return View::render('product_detailed', array(
                'cur_cat' => $cur_cat,
                'category' => $category,
                'cur_prod' => $cur_prod
        ));
    }
}