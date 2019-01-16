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
use Framework\Session;
use Zaine\Log;

class BasketService
{
    private $basketProductRepository;
    private $basketRepository;
    private $session;

    private $authService;

    public function __construct(BasketProductRepository $basketProductRepository, BasketRepository $basketRepository,
                                Session $session)
    {
        $this->basketProductRepository = $basketProductRepository;
        $this->basketRepository = $basketRepository;
        $this->session = $session;
        // todo add AuthService to construct
        $this->authService = Dispatcher::get(AuthService::class);
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

    public function getBasketProducts()
    {
        if ($this->authService->isAuth()) {
            return $this->basketProductRepository->findByUserId($this->authService->getUserId());
        } else {
            if ($this->session->sessionExist()) {
                $basketProductsId = $this->session->get('basketProducts');
                $result = [];
                foreach ($basketProductsId as $basketProductId) {
                    try {
                        $result[] = $this->basketProductRepository->findById($basketProductId);
                    } catch (BasketProductNotExistExtension $e) {
                        $logger = Dispatcher::get(Log::class);
                        $logger->error('BasketProductNotExistExtension');
                        $logger->error($e->getTraceAsString());
                    }
                }
                return $result;
            } else {
                return array();
            }
        }
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

    public function getCountProductsAtUserBasket(): int
    {
        if ($this->authService->isAuth()) {
            return $this->basketProductRepository->getCountProductsAtUserBasket(
                    (new User)->setId($this->authService->getUserId())
            );
        } else {
            if ($this->session->sessionExist()) {
                return count($this->session->get('basketProducts'));
            } else {
                return 0;
            }
        }
    }

    public function updateProductCountAtBasket(int $id, int $count): BasketProduct
    {
        try {
            $basketProduct = $this->basketProductRepository->findById($id);
            $basketProduct->setCount($count);
            return $this->basketProductRepository->update($basketProduct);
        } catch (BasketProductNotExistExtension $e) {
            // todo add exception
        }
        return new BasketProduct();
    }

    public function deleteProductAtBasket(int $id): void
    {
        $basketProduct = (new BasketProduct())->setId($id);
        $this->basketProductRepository->delete($basketProduct);
    }
}