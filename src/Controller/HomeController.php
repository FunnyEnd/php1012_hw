<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Framework\BaseController;
use Framework\View;

class HomeController extends BaseController
{
    private $productRepository;
    private $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function showHome(): string
    {
        $category = $this->categoryRepository->findAll();
        $categoryFirstProducts = $this->productRepository->findByCategoryId(1);

        return View::render('home', ['category' => $category, 'categoryFirstProducts' => $categoryFirstProducts]);
    }
}