<?php

namespace App\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Request\CategoryShowRequest;
use Framework\HTTP\Response;
use Framework\TemplateEngine\View;

class CategoryController
{
    private $product;
    private $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function showCategory(CategoryShowRequest $request): string
    {
        $id = $request->get('id');
        $cur_cat = $this->category->getById($id);

        if (empty($cur_cat)){
            Response::setResponseCode(404);
            // todo: page category don`t exist
            return View::render('404');
        }

        $cur_cat_products = $this->product->getByCatId($id);
        $category = $this->category->getAll();

        return View::render('product_list_at_category', array(
                'cur_cat' => $cur_cat,
                'cur_cat_products' => $cur_cat_products,
                'category' => $category
        ));
    }
}