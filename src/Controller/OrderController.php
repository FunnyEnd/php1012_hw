<?php

namespace App\Controller;

use App\Services\AuthService;
use App\Services\Basket\BasketServiceFactory;
use App\Services\OrderService;
use App\View\UserView;
use Framework\Controller;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class OrderController extends Controller
{
    private $orderService;
    private $basketService;

    public function __construct(
        OrderService $orderService,
        BasketServiceFactory $basketServiceFactory,
        AuthService $authService
    ) {
        $this->orderService = $orderService;
        $this->basketService = $basketServiceFactory->getBasketService($authService->isAuth());
    }

    public function index()
    {
        return UserView::render('order', [
            'email' => '',
            'firstName' => '',
            'lastName' => '',
            'phone' => '',
            'city' => '',
            'stock' => '',
            'comment' => '',
        ]);
    }

    public function store(Request $request)
    {
        $order = $this->orderService->createFromBasket($request);
        $this->basketService->drop();

        return UserView::render('order_created', [
                'orderId' => $order->getId()
        ]);
    }
}