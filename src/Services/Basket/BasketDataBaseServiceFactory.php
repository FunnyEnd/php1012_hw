<?php

namespace App\Services\Basket;

use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Services\AuthService;

class BasketDataBaseServiceFactory implements BasketServiceFactory
{
    private $basketProductRepository;
    private $authService;
    private $basketRepository;

    public function __construct(BasketProductRepository $basketProductRepository, AuthService $authService,
                                BasketRepository $basketRepository)
    {
        $this->basketProductRepository = $basketProductRepository;
        $this->authService = $authService;
        $this->basketRepository = $basketRepository;
    }

    public function getBasketService(): BasketService
    {
        return new BasketDataBaseService(
                $this->basketProductRepository,
                $this->authService,
                $this->basketRepository
        );
    }
}