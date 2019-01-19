<?php

namespace App\Services\Basket;

use Framework\Dispatcher;

class BasketServiceFactory
{
    public function getBasketService(bool $auth): BasketService
    {
        if ($auth) {
            return Dispatcher::get(BasketDataBaseService::class);
        }

        return Dispatcher::get(BasketSessionService::class);
    }
}