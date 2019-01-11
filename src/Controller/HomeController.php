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
        try {
            $basketProduct = $basketProductRepository->findById(3);
            var_dump($basketProduct);
        } catch (BasketProductNotExistExtension $e) {
            var_dump($e->getMessage());
            die($e->getTraceAsString());
        }



        return UserView::render('home', [
                'categoryFirstProducts' => $this->productRepository->findByCategoryId(1)
        ]);
    }
}