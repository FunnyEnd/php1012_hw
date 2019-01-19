<?php

namespace App\Services;

use App\Models\ContactPerson;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Repository\ContactPersonRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Services\Basket\BasketServiceFactory;
use Framework\HTTP\Request;

class OrderService
{
    private $contactPersonRepository;
    private $authService;
    private $orderRepository;
    private $basketService;
    private $orderProductRepository;

    public function __construct(ContactPersonRepository $contactPersonRepository, AuthService $authService,
                                OrderRepository $orderRepository, BasketServiceFactory $basketServiceFactory,
                                OrderProductRepository $orderProductRepository)
    {
        $this->contactPersonRepository = $contactPersonRepository;
        $this->authService = $authService;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->basketService = $basketServiceFactory->getBasketService($this->authService->isAuth());
    }

    public function createFromBasket(Request $request): Order
    {
        $contactPerson = $this->contactPersonRepository->save((new ContactPerson())
                ->setFirstName($request->post('first-name'))
                ->setLastName($request->post('last-name'))
                ->setEmail($request->post('email'))
                ->setCity($request->post('city'))
                ->setStock($request->post('stock'))
                ->setPhone($request->post('phone')));

        $order = $this->orderRepository->save((new Order())
                ->setUser($this->authService->getCurrentUser())
                ->setContactPerson($contactPerson)
                ->setConfirm(0)
                ->setComment($request->post('comment')));

        $basketsProducts = $this->basketService->getProducts();

        foreach ($basketsProducts as $basketsProduct) {
            $this->orderProductRepository->save((new OrderProduct())
                    ->setOrder($order)
                    ->setProduct($basketsProduct->getProduct())
                    ->setPrice($basketsProduct->getProduct()->getPriceAtCoins())
                    ->setCount($basketsProduct->getCount()));
        }

        return $order;
    }
}