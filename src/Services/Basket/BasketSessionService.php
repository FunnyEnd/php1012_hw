<?php

namespace App\Services\Basket;

use App\Extensions\ProductNotExistExtension;
use App\Models\BasketProduct;
use App\Repository\ProductRepository;
use Framework\Dispatcher;
use Framework\HTTP\Request;
use Framework\Session;
use Zaine\Log;

class BasketSessionService implements BasketService
{
    private $session;
    private $productRepository;

    public function __construct(Session $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function getProducts(): array
    {
        $products = array();
        try {
            if ($this->session->sessionExist()) {
                $basketProducts = $this->session->get('basketProducts');
                // really, in this scope, $id this is product id
                foreach ($basketProducts as $id => $count) {
                    $products[] = (new BasketProduct())
                            ->setProduct($this->productRepository->findById($id))
                            ->setCount($count)
                            ->setId($id);
                }
            }
        } catch (ProductNotExistExtension $e) {
            return array();
        }

        return $products;
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
        $this->session->start();
        $basketProducts = $this->session->get('basketProducts');

        if (!is_array($basketProducts))
            $basketProducts = [];

        if (array_key_exists($request->post('id'), $basketProducts))
            $basketProducts[$request->post('id')] += $request->post('count');
        else
            $basketProducts[$request->post('id')] = $request->post('count');

        $this->session->set('basketProducts', $basketProducts);
    }

    public function getCountProducts(): int
    {
        $count = 0;

        if ($this->session->sessionExist()) {
            $count = count($this->session->get('basketProducts'));
        }

        return $count;
    }

    public function updateProduct(Request $request): BasketProduct
    {
        if ($this->session->sessionExist()) {
            $id = $request->get('id');
            $count = $request->put('count');
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

        return null;
    }

    public function deleteProduct(Request $request): void
    {
        $id = $request->get('id');
        $basketProducts = $this->session->get('basketProducts');

        if (is_array($basketProducts) && array_key_exists($id, $basketProducts)) {
            unset($basketProducts[$id]);
            $this->session->set('basketProducts', $basketProducts);
        }
    }
}
