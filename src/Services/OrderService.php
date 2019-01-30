<?php

namespace App\Services;

use App\Models\ContactPerson;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Repository\ContactPersonRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Services\Basket\BasketServiceFactory;
use Framework\Config;
use Framework\HTTP\Request;

class OrderService
{
    private $contactPersonRepository;
    private $authService;
    private $orderRepository;
    private $basketService;
    private $orderProductRepository;
    private $productRepository;

    public function __construct(
        ContactPersonRepository $contactPersonRepository,
        AuthService $authService,
        OrderRepository $orderRepository,
        BasketServiceFactory $basketServiceFactory,
        OrderProductRepository $orderProductRepository,
        ProductRepository $productRepository
    )
    {
        $this->contactPersonRepository = $contactPersonRepository;
        $this->authService = $authService;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->basketService = $basketServiceFactory->getBasketService($this->authService->isAuth());
        $this->productRepository = $productRepository;
    }

    public function createFromBasket(Request $request): Order
    {
        $contactPerson = $this->contactPersonRepository->save((new ContactPerson())
            ->setFirstName($request->fetch('post', 'first-name'))
            ->setLastName($request->fetch('post', 'last-name'))
            ->setEmail($request->fetch('post', 'email'))
            ->setCity($request->fetch('post', 'city'))
            ->setStock($request->fetch('post', 'stock'))
            ->setPhone($request->fetch('post', 'phone')));

        $order = $this->orderRepository->save((new Order())
            ->setUser($this->authService->getCurrentUser())
            ->setContactPerson($contactPerson)
            ->setConfirm(0)
            ->setComment($request->fetch('post', 'comment')));

        $basketsProducts = $this->basketService->getProducts();

        foreach ($basketsProducts as $basketsProduct) {
            $product = $this->productRepository->findById($basketsProduct->getProduct()->getId());
            $product->setAvailability($product->getAvailability() - $basketsProduct->getCount());
            $this->productRepository->update($product);

            $this->orderProductRepository->save((new OrderProduct())
                ->setOrder(new Order($order))
                ->setProduct($basketsProduct->getProduct())
                ->setPrice($basketsProduct->getProduct()->getPriceAtCoins())
                ->setCount($basketsProduct->getCount()));
        }

        return new Order($order);
    }

    public function getCountPages()
    {
        $count = Config::get('count_orders_at_page');
        $countOrders = (int)$this->orderRepository->findCount('', []);

        if ($countOrders < $count) {
            return 1;
        }

        return ceil($countOrders / $count);
    }

    public function getOrders(int $page): array
    {
        $count = Config::get('count_orders_at_page');
        $from = ($page - 1) * $count;

        $orders = $this->orderRepository->findAll('', [
            'from' => $from,
            'count' => $count
        ]);

        return $orders;
    }

    public function getOrder(Request $request): Order
    {
        $order = $this->orderRepository->findById($request->fetch('get', 'id'));
        $order->setContactPerson($this->contactPersonRepository->findById($order->getContactPerson()->getId()));

        return new Order($order);
    }

    public function getProducts(Request $request): array
    {
        return $this->orderProductRepository->findAll('order_id = :id', [
            'id' => $request->fetch('get', 'id')
        ]);
    }

    public function confirm(Request $request)
    {
        $order = $this->orderRepository->findById($request->fetch('get', 'id'));
        $this->orderRepository->update($order->setConfirm(1));
    }

}
