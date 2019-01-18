<?php

namespace App\Services;

use App\Models\ContactPerson;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ContactPersonRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Services\Basket\BasketDataBaseServiceFactory;
use App\Services\Basket\BasketSessionServiceFactory;
use Framework\HTTP\Request;
use Framework\Session;

class OrderService
{
    private $contactPersonRepository;
    private $authService;
    private $orderRepository;
    private $basketService;
    private $orderProductRepository;
    private $session;

    public function __construct(ContactPersonRepository $contactPersonRepository, AuthService $authService,
                                OrderRepository $orderRepository, BasketProductRepository $basketProductRepository,
                                BasketRepository $basketRepository, Session $session,
                                ProductRepository $productRepository, OrderProductRepository  $orderProductRepository)
    {
        $this->contactPersonRepository = $contactPersonRepository;
        $this->authService = $authService;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->session = $session;

        if ($this->authService->isAuth()) {
            $basketServiceFactory = new BasketDataBaseServiceFactory(
                    $basketProductRepository,
                    $this->authService,
                    $basketRepository
            );
            $this->basketService = $basketServiceFactory->getBasketService();
        } else {
            $basketServiceFactory = new BasketSessionServiceFactory(
                    $session,
                    $productRepository
            );
            $this->basketService = $basketServiceFactory->getBasketService();
        }
    }

    public function createFromBasket(Request $request): Order
    {
        // todo check empty basket
        // todo valid fields
        $contactPerson = (new ContactPerson())
                ->setFirstName($request->post('first-name'))
                ->setLastName($request->post('last-name'))
                ->setEmail($request->post('email'))
                ->setCity($request->post('city'))
                ->setStock($request->post('stock'))
                ->setPhone($request->post('phone'));

        // todo: create user ContactPerson
        $contactPerson = $this->contactPersonRepository->save($contactPerson);

        if ($this->authService->isAuth())
            $user = (new User())->setId($this->authService->getUserId());
        else
            $user = (new User())->setId(0);

        $order = (new Order())
                ->setUser($user)
                ->setContactPerson($contactPerson)
                ->setConfirm(0)
                ->setComment($request->post('comment'));

        $order = $this->orderRepository->save($order);

        $basketsProducts = $this->basketService->getProducts();

        foreach ($basketsProducts as $basketsProduct) {
            $orderProduct = (new OrderProduct())
                    ->setOrder($order)
                    ->setProduct($basketsProduct->getProduct())
                    ->setPrice($basketsProduct->getProduct()->getPriceAtCoins())
                    ->setCount($basketsProduct->getCount());

            $this->orderProductRepository->save($orderProduct);
        }

        // dropBasket
        $this->basketService->deleteAllProducts();
        $this->basketService->deleteBasket();

        if (!$this->authService->isAuth())
            $this->session->destroy();

        return $order;
    }
}