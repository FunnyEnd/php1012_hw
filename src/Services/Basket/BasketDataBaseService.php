<?php

namespace App\Services\Basket;

use App\Extensions\BasketNotExistExtension;
use App\Extensions\BasketProductNotExistExtension;
use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Product;
use App\Models\User;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Services\AuthService;
use Framework\Dispatcher;
use Framework\HTTP\Request;
use Zaine\Log;

class BasketDataBaseService implements BasketService
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

    public function getProducts(): array
    {
        return $this->basketProductRepository->findByUserId($this->authService->getUserId());
    }

    public function getTotalPrice(): float
    {
        $products = $this->getProducts();
        $totalPrice = 0;

        foreach ($products as $product) {
            $totalPrice += $product->getPriceAtCoins();
        }

        return $totalPrice / 100;
    }

    public function addProduct(Request $request): void
    {
        $userId = $this->authService->getUserId();
        $basketProduct = new BasketProduct();
        $basketProduct->setProduct((new Product())->setId($request->post('id')));

        try {
            $basket = $this->basketRepository->findByUserId($userId);
        } catch (BasketNotExistExtension $e) {
            $basket = new Basket();
            $basket->setUser((new User())->setId($userId));
            $basket = $this->basketRepository->save($basket);
        }

        $basketProduct->setBasket($basket);
        $basketProduct->setCount($request->post('count'));

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

    public function getCountProducts(): int
    {
        return $this->basketProductRepository->getCountProductsAtUserBasket(
                (new User)->setId($this->authService->getUserId())
        );
    }

    public function updateProduct(Request $request): BasketProduct
    {
        try {
            $basketProduct = $this->basketProductRepository->findById($request->get('id'));
            $basketProduct->setCount($request->put('count'));
            return $this->basketProductRepository->update($basketProduct);
        } catch (BasketProductNotExistExtension $e) {
            $logger = Dispatcher::get(Log::class);
            $logger->error("BasketProductNotExistExtension");
            $logger->error($e->getTraceAsString());
        }

        return null;
    }

    public function deleteProduct(Request $request): void
    {
        $basketProduct = (new BasketProduct())->setId($request->get('id'));
        $this->basketProductRepository->delete($basketProduct);
    }
}
