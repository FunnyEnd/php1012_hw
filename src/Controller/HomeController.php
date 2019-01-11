<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\View\UserView;
use Framework\BaseController;


class HomeController extends BaseController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function showHome(): string
    {
        return UserView::render('home', [
                'categoryFirstProducts' => $this->productRepository->findByCategoryId(1)
        ]);
    }
}