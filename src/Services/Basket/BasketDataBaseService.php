<?php

namespace App\Services\Basket;

use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Product;
use App\Models\User;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Services\AuthService;
use Framework\HTTP\Request;

class BasketDataBaseService extends AbstractBasketService
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

    public function addProduct(Request $request): void
    {
        $userId = $this->authService->getUserId();
        $product = (new Product())->setId($request->post('id'));
        $basket = $this->basketRepository->findByUserId($userId);

        if ($basket === null) {
            $basket = $this->basketRepository->save((new Basket())
                ->setUser((new User())->setId($userId)));
        }

        $basketProductFromDataBase = $this->basketProductRepository->findByProductIdAndBasketId(
            $product->getId(),
            $basket->getId()
        );

        if ($basketProductFromDataBase->isEmpty()) {
            $this->basketProductRepository->save((new BasketProduct())
                ->setCount($request->post('count'))
                ->setProduct($product)
                ->setBasket($basket)
            );
        } else {
            $this->basketProductRepository->update((new BasketProduct())
                ->setCount($request->post('count') + $basketProductFromDataBase->getCount())
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
        $basketProduct = $this->basketProductRepository->findById($request->get('id'));

        if ($basketProduct->isEmpty()) {
            return null;
        }

        return new BasketProduct($this->basketProductRepository->update(
            $basketProduct->setCount($request->put('count'))
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
