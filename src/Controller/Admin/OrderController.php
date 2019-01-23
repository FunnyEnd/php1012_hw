<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Services\OrderService;
use App\View\AdminView;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class OrderController extends BaseController
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): string
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $currentPage = 1;
        if ($request->issetGet('page')) {
            $currentPage = $request->get('page');
        }

        $countPages = $this->orderService->getCountPages();
        $orders = $this->orderService->getOrders($currentPage);

//        var_dump($orders);

        return AdminView::render('orders', [
            'orders' => $orders,
            'countPages' => $countPages,
            'currentPage' => $currentPage
        ]);
    }


}