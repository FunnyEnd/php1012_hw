<?php

namespace App\Controller;

use App\Request\CategoryShowRequest;
use App\Services\CategoryService;
use App\Services\ProductService;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\TemplateEngine\View;

class CategoryController
{
    private $productService;
    private $categoryService;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
    }

    public function showCategory(Request $request): string
    {
        $id = $request->get('id');
        $currentCategory = $this->categoryService->findById($id);

        if (empty($currentCategory)) {
            Response::setResponseCode(404);
            // todo: page category don`t exist
            return View::render('404');
        }

        $productsAtCurrentCategory = $this->productService->findByCategoryId($id);
        $categoryList = $this->categoryService->findAll();

        return View::render('product_list_at_category', array(
                'cur_cat' => $currentCategory,
                'cur_cat_products' => $productsAtCurrentCategory,
                'category' => $categoryList
        ));
    }
}