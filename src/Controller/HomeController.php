<?php

namespace App\Controller;

use App\Extensions\BasketProductNotExistExtension;
use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Product;
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
        $p = new Product();
        $p->setId(10);
        $b = new Basket();
        $b->setId(1);
        $bp = new BasketProduct();
        $bp->setBasket($b);
        $bp->setProduct($p);
        $bp->setCount(10);

        $basketProduct = $basketProductRepository->save($bp);
        var_dump($basketProduct);



        return UserView::render('home', [
                'categoryFirstProducts' => $this->productRepository->findByCategoryId(1)
        ]);
    }
}