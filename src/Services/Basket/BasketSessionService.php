<?php

namespace App\Services\Basket;

use App\Models\BasketProduct;
use App\Repository\ProductRepository;
use Framework\HTTP\Request;
use Framework\Session;

class BasketSessionService extends AbstractBasketService
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

        return $products;
    }

    public function addProduct(Request $request): void
    {
        $this->session->start();
        if ($this->session->keyExist('basketProducts')) {
            $basketProducts = $this->session->get('basketProducts');
        } else {
            $basketProducts = null;
        }

        if (!is_array($basketProducts)) {
            $basketProducts = [];
        }

        $product = $this->productRepository->findById($request->fetch('post', 'id'));

        if (array_key_exists($request->fetch('post', 'id'), $basketProducts)) {
            $count = intval($request->fetch('post', 'count')) +
                $basketProducts[$request->fetch('post', 'id')];
        } else {
            $count = intval($request->fetch('post', 'count'));
        }

        if ($product->getAvailability() < $count) {
            $count = $product->getAvailability();
        }

        $basketProducts[$request->fetch('post', 'id')] = $count;

        $this->session->set('basketProducts', $basketProducts);
    }

    public function getCountProducts(): int
    {
        $count = 0;

        if ($this->session->sessionExist()) {
            if ($this->session->keyExist('basketProducts')) {
                $basketProducts = $this->session->get('basketProducts');
            } else {
                $basketProducts = [];
            }

            $count = count($basketProducts);
        }

        return $count;
    }

    public function updateProduct(Request $request): BasketProduct
    {
        if (!$this->session->sessionExist()) {
            return new BasketProduct();
        }

        $id = $request->fetch('get', 'id');
        $basketProducts = $this->session->get('basketProducts');

        if (!array_key_exists($id, $basketProducts)) {
            return new BasketProduct();
        }

        $product = $this->productRepository->findById($id);
        $count = $request->fetch('put', 'count');

        if ($product->getAvailability() < $count) {
            $count = $product->getAvailability();
        }

        $basketProducts[$id] = $count;
        $this->session->set('basketProducts', $basketProducts);

        return (new BasketProduct())
            ->setId($id)
            ->setProduct($this->productRepository->findById($id))
            ->setCount($count);
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

    public function drop(): void
    {
        $this->session->set('basketProducts', []);
    }

    public function isEmpty(): bool
    {
        $basketProducts = $this->session->get('basketProducts');
        return empty($basketProducts);
    }
}
