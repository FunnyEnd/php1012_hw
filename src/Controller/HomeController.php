<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\AuthService;
use Framework\BaseController;
use Framework\View;

class HomeController extends BaseController
{
    private $productRepository;
    private $categoryRepository;
    private $authService;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository, AuthService $authService)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authService = $authService;
    }

    public function showHome(): string
    {
        $category = $this->categoryRepository->findAll();
        $categoryFirstProducts = $this->productRepository->findByCategoryId(1);
        $isAuth = $this->authService->isAuth();
        return View::render('home', [
                'category' => $category,
                'auth' => $isAuth,
                'categoryFirstProducts' => $categoryFirstProducts
        ]);
    }
}