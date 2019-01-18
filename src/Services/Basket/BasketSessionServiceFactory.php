<?php

namespace App\Services\Basket;

use App\Repository\ProductRepository;
use Framework\Session;

class BasketSessionServiceFactory implements BasketServiceFactory
{
    private $session;
    private $productRepository;

    public function __construct(Session $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function getBasketService(): BasketService
    {
        return new BasketSessionService(
                $this->session,
                $this->productRepository
        );
    }
}