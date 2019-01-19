<?php

namespace App\Services\Basket;


abstract class AbstractBasketService implements BasketService
{
    public function getTotalPrice(): float
    {
        $products = $this->getProducts();
        $totalPrice = 0;

        foreach ($products as $product) {
            $totalPrice += $product->getPriceAtCoins();
        }

        return $totalPrice / 100;
    }
}
