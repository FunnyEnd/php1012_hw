<?php

namespace App\Controller;

use App\Services\CategoryService;
use App\Services\ProductService;
use Framework\BaseController;
use Framework\TemplateEngine\View;

class HomeController extends BaseController
{
    private $productService;
    private $categoryService;

    public function __construct()
    {
        parent::__construct();
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
    }

    public function showHome(): string
    {
        $categoryList = $this->categoryService->findAll();
        $catFirstProducts = $this->productService->findByCategoryId(1);
        $catSecondProducts = $this->productService->findByCategoryId(2);
        $catThirdProducts = $this->productService->findByCategoryId(3);

        return View::render('home', array(
                'category' => $categoryList,
                'cat_1_products' => $catFirstProducts,
                'cat_2_products' => $catSecondProducts,
                'cat_3_products' => $catThirdProducts
        ));
    }
}