<?php

namespace App\Services\Basket;

use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Product;
use App\Models\User;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use App\Services\AuthService;
use Framework\Dispatcher;
use Framework\HTTP\Request;
use Zaine\Log;

class BasketDataBaseService extends AbstractBasketService
{
    private $basketProductRepository;
    private $authService;
    private $basketRepository;
    private $productRepository;

    public function __construct(
        BasketProductRepository $basketProductRepository,
        AuthService $authService,
        BasketRepository $basketRepository,
        ProductRepository $productRepository
    ) {
        $this->basketProductRepository = $basketProductRepository;
        $this->authService = $authService;
        $this->basketRepository = $basketRepository;
        $this->productRepository = $productRepository;
    }

    public function getProducts(): array
    {
        return $this->basketProductRepository->findByUserId($this->authService->getUserId());
    }

    public function addProduct(Request $request): void
    {
        $userId = $this->authService->getUserId();
        $product = $this->productRepository->findById($request->fetch('post', 'id'));

        $basket = $this->basketRepository->findByUserId($userId);

        if ($basket->isEmpty()) {
            $basket = $this->basketRepository->save((new Basket())
                ->setUser((new User())->setId($userId)));
        }

        $basketProductFromDataBase = $this->basketProductRepository->findByProductIdAndBasketId(
            $product->getId(),
            $basket->getId()
        );

        if ($basketProductFromDataBase->isEmpty()) {
            $count = intval($request->fetch('post', 'count'));
            if ($product->getAvailability() < $count) {
                $count = $product->getAvailability();
            }

            $this->basketProductRepository->save(
                (new BasketProduct())
                    ->setCount($count)
                    ->setProduct($product)
                    ->setBasket($basket)
            );
        } else {
            $count = intval($request->fetch('post', 'count')) + $basketProductFromDataBase->getCount();
            if ($product->getAvailability() < $count) {
                $count = $product->getAvailability();
            }

            $this->basketProductRepository->update(
                (new BasketProduct())->setCount($count)
                    ->setProduct($product)
                    ->setBasket($basket)
            );
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
        $basketProduct = $this->basketProductRepository->findById($request->fetch('get', 'id'));

        if ($basketProduct->isEmpty()) {
            return new BasketProduct();
        }

        $product = $this->productRepository->findById($basketProduct->getProduct()->getId());
        $count = $request->fetch('put', 'count');

        if ($product->getAvailability() < $count) {
            $count = $product->getAvailability();
        }

        return new BasketProduct($this->basketProductRepository->update(
            $basketProduct->setCount($count)
        ));
    }

    public function deleteProduct(Request $request): void
    {
        $basketProduct = (new BasketProduct())->setId($request->get('id'));
        $this->basketProductRepository->delete($basketProduct);
    }

    public function drop(): void
    {
        $basket = $this->basketRepository->findByUserId($this->authService->getUserId());

        if ($basket !== null) {
            $this->basketProductRepository->deleteByBasketId($basket->getId());
            $this->basketRepository->delete($basket);
        }
    }

    public function isEmpty(): bool
    {
        $userId = $this->authService->getUserId();
        $basket = $this->basketRepository->findByUserId($userId);

        if ($basket === null) {
            return true;
        }

        $basketProducts = $this->basketProductRepository->findByUserId($userId);
        return empty($basketProducts);
    }
}
