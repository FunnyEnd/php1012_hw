<?php

namespace App\Services\Basket;

use App\Models\BasketProduct;
use Framework\HTTP\Request;

interface BasketService
{
    public function getProducts(): array;

    public function getTotalPrice(): float;

    public function addProduct(Request $request): void;

    public function getCountProducts(): int;

    public function updateProduct(Request $request): BasketProduct;

    public function deleteProduct(Request $request): void;

    public function deleteAllProducts(): void;

    public function deleteBasket():void;
}
