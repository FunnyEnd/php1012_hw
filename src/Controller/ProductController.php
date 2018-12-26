<?php

namespace App\Controller;

use App\Request\ProductShowRequest;
use App\Services\CategoryService;
use App\Services\ProductService;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\TemplateEngine\View;

class ProductController
{
    private $productService;
    private $categoryService;

    function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
    }

    public function showProduct(Request $request): string
    {
        $id = $request->get("id");
        $cur_prod = $this->productService->findById($id);

        if(empty($cur_prod)){
            Response::setResponseCode(404);
            // todo: page product don`t exist
            return View::render('404');
        }

        $cur_cat = $this->categoryService->findById($cur_prod['cat']);

        if(empty($cur_cat)){
            Response::setResponseCode(404);
            // todo: page category don`t exist
            return View::render('404');
        }

        $category = $this->categoryService->findAll();

        return View::render('product_detailed', array(
                'cur_cat' => $cur_cat,
                'category' => $category,
                'cur_prod' => $cur_prod
        ));
    }
}