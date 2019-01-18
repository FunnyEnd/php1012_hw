<?php

namespace App\Services\Basket;

interface BasketServiceFactory
{
    public function getBasketService(): BasketService;
}