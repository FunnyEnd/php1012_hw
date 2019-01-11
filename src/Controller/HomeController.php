<?php

namespace App\Controller;

use App\Extensions\BasketProductNotExistExtension;
use App\Repository\BasketProductRepository;
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

    public function showHome(BasketProductRepository $basketProductRepository): string
    {
        $basketProduct = $basketProductRepository->findByUserId(10);
        var_dump($basketProduct);



        return UserView::render('home', [
                'categoryFirstProducts' => $this->productRepository->findByCategoryId(1)
        ]);
    }
}