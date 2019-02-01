<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Repository\OrderProductRepository;
use App\Services\OrderService;
use App\View\AdminView;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class OrderController extends BaseController
{
    private $orderService;
    private $orderProductRepository;

    public function __construct(OrderService $orderService, OrderProductRepository $orderProductRepository)
    {
        $this->orderService = $orderService;
        $this->orderProductRepository = $orderProductRepository;
    }

    public function list(Request $request): string
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $currentPage = 1;
        if ($request->exist('get', 'page')) {
            $currentPage = $request->fetch('get', 'page');
        }

        $countPages = $this->orderService->getCountPages();
        $orders = $this->orderService->getOrders($currentPage);

        return AdminView::render('orders', [
            'orders' => $orders,
            'countPages' => $countPages,
            'currentPage' => $currentPage
        ]);
    }

    public function index(Request $request)
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        $order = $this->orderService->getOrder($request);
        $orderProducts = $this->orderService->getProducts($request);

        return AdminView::render('order_detail', [
            'order' => $order,
            'orderProducts' => $orderProducts
        ]);
    }

    public function confirm(Request $request)
    {
        $this->orderService->confirm($request);

        return Response::redirect('/admin/order/' . $request->get('id'));
    }
}