<?php

namespace App\Controller;

use App\Services\AuthService;
use App\Services\Basket\BasketServiceFactory;
use App\Services\OrderService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class OrderController extends BaseController
{
    private $orderService;
    private $basketService;

    public function __construct(OrderService $orderService, BasketServiceFactory $basketServiceFactory,
                                AuthService $authService)
    {
        $this->orderService = $orderService;
        $this->basketService = $basketServiceFactory->getBasketService($authService->isAuth());
    }

    public function index()
    {
        if ($this->basketService->isEmpty()) {
            return Response::redirect('/');
        }

        return UserView::render('order');
    }

    public function store(Request $request)
    {
        if ($this->basketService->isEmpty()) {
            return Response::redirect('/');
        }

        $order = $this->orderService->createFromBasket($request);
        $this->basketService->drop();

        return UserView::render('order_created', [
                'orderId' => $order->getId()
        ]);
    }
}