<?php

namespace App\Services;

use App\Extensions\BasketProductNotExistExtension;
use App\Models\BasketProduct;
use App\Repository\BasketProductRepository;
use Framework\Dispatcher;
use Zaine\Log;

class BasketService
{
    private $basketProductRepository;

    public function __construct(BasketProductRepository $basketProductRepository)
    {
        $this->basketProductRepository = $basketProductRepository;
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
}