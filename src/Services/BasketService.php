<?php

namespace App\Services;

use App\Extensions\BasketNotExistExtension;
use App\Extensions\BasketProductNotExistExtension;
use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\User;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use Framework\Dispatcher;
use Zaine\Log;

class BasketService
{
    private $basketProductRepository;
    private $basketRepository;

    public function __construct(BasketProductRepository $basketProductRepository, BasketRepository $basketRepository)
    {
        $this->basketProductRepository = $basketProductRepository;
        $this->basketRepository = $basketRepository;
    }

    public function addProductToBasket(BasketProduct $basketProduct): void
    {

        if ($this->basketProductRepository->isProductExist($basketProduct)) {
            try {

                $basketProductFromDataBase = $this->basketProductRepository->findByProductIdAndBasketId(
                        $basketProduct->getProduct()->getId(),
                        $basketProduct->getBasket()->getId()
                );

                $basketProduct->setCount($basketProduct->getCount() + $basketProductFromDataBase->getCount());
                $this->basketProductRepository->update($basketProduct);
            } catch (BasketProductNotExistExtension $e) {
                $logger = Dispatcher::get(Log::class);
                $logger->error("Product with id {$basketProduct->getProduct()->getId()} don`t exist " .
                        " at basket with id {$basketProduct->getBasket()->getId()}.");
            }
        } else {
            $this->basketProductRepository->save($basketProduct);
        }

    }

    public function deleteProductFromBasket(BasketProduct $basketProduct): void
    {
        $this->basketProductRepository->delete($basketProduct);
    }

    public function calculateTotalPrice(array $products): float
    {
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->getPriceAtCoins();
        }
        $totalPrice /= 100;
        return $totalPrice;
    }

    public function getBasketByUserId(int $userId): Basket
    {
        try {
            $basket = $this->basketRepository->findByUserId($userId);
        } catch (BasketNotExistExtension $e) {
            $basket = new Basket();
            $basket->setUser((new User())->setId($userId));
            $basket = $this->basketRepository->save($basket);
        }
        return $basket;
    }
}