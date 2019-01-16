<?php

namespace App\Services;

use App\Extensions\BasketNotExistExtension;
use App\Extensions\BasketProductNotExistExtension;
use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\User;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
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

    public function calculateTotalPrice(array $products): float
    {
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->getPriceAtCoins();
        }
        $totalPrice /= 100;
        return $totalPrice;
    }

    public function getBasketProducts(): array
    {
        if ($this->authService->isAuth()) {
            return $this->basketProductRepository->findByUserId($this->authService->getUserId());
        } else {
            if ($this->session->sessionExist()) {
                $basketProducts = $this->session->get('basketProducts');
                $result = [];
                // really, in this scope, $basketProductId this is product id
                foreach ($basketProducts as $basketProductId => $count) {
                    $productRepository = Dispatcher::get(ProductRepository::class);
                    $basketProduct = (new BasketProduct())
                            ->setProduct($productRepository->findById($basketProductId))
                            ->setCount($count)
                            ->setId($basketProductId);

                    $result[] = $basketProduct;
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
        if ($this->authService->isAuth()) {
            try {
                $basketProduct = $this->basketProductRepository->findById($id);
                $basketProduct->setCount($count);
                return $this->basketProductRepository->update($basketProduct);
            } catch (BasketProductNotExistExtension $e) {
                $logger = Dispatcher::get(Log::class);
                $logger->error("BasketProductNotExistExtension");
                $logger->error($e->getTraceAsString());
            }
        } else if ($this->session->sessionExist()) {
            $basketProducts = $this->session->get('basketProducts');
            if (array_key_exists($id, $basketProducts)) {
                $basketProducts[$id] = $count;
                $this->session->set('basketProducts', $basketProducts);
                $productRepository = Dispatcher::get(ProductRepository::class);
                return (new BasketProduct())
                        ->setId($id)
                        ->setProduct($productRepository->findById($id))
                        ->setCount($count);
            } else {
                $logger = Dispatcher::get(Log::class);
                $logger->error("BasketProductNotExistExtension at updateProductCountAtBasket method.");
            }
        }
        return new BasketProduct();
    }

    public function deleteProductAtBasket(int $id): void
    {
        if ($this->authService->isAuth()) {
            $basketProduct = (new BasketProduct())->setId($id);
            $this->basketProductRepository->delete($basketProduct);
        } else if ($this->session->sessionExist()) {
            $basketProducts = $this->session->get('basketProducts');
            if (array_key_exists($id, $basketProducts)) {
                unset($basketProducts[$id]);
                $this->session->set('basketProducts', $basketProducts);
            }
        }
    }

    public function deleteBasket()
    {
        if ($this->authService->isAuth()) {
            try {
                $this->basketRepository->delete(
                        $this->basketRepository->findByUserId($this->authService->getUserId())
                );
            } catch (BasketNotExistExtension $e) {
                $logger = Dispatcher::get(Log::class);
                $logger->error("BasketProductNotExistExtension at deleteBasket method.");
            }
        } else if ($this->session->sessionExist()) {
            $this->session->set('basketProducts', []);
        }
    }
}