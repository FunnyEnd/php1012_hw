<?php

namespace App\Controller;

use App\Services\ProductService;
use App\View\UserView;
use Framework\BaseController;

class HomeController extends BaseController
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function showHome(): string
    {
        return UserView::render('home', [
                'categoryFirstProducts' => $this->productService->getPopular()
        ]);
    }
}