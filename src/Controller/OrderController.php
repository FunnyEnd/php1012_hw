<?php

namespace App\Controller;

use App\Services\OrderService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;

class OrderController extends BaseController
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        // todo check empty basket
        return UserView::render('order');
    }

    public function store(Request $request)
    {
        $order = $this->orderService->createFromBasket($request);

        return UserView::render('order_created', [
                'orderId' => $order->getId()
        ]);
//        // todo check empty basket
//        // todo valid fields
//        $contactPerson = (new ContactPerson())
//                ->setFirstName($request->post('first-name'))
//                ->setLastName($request->post('last-name'))
//                ->setEmail($request->post('email'))
//                ->setCity($request->post('city'))
//                ->setStock($request->post('stock'))
//                ->setPhone($request->post('phone'));
//
//        // todo: create user ContactPerson
//        $contactPerson = $contactPersonRepository->save($contactPerson);
//
//        if ($authService->isAuth())
//            $user = (new User())->setId($authService->getUserId());
//        else
//            $user = (new User())->setId(0);
//
//        $order = (new Order())
//                ->setUser($user)
//                ->setContactPerson($contactPerson)
//                ->setConfirm(0)
//                ->setComment($request->post('comment'));
//
//        $order = $orderRepository->save($order);
//
//        $basketsProducts = $basketService->getProducts();
//
//        foreach ($basketsProducts as $basketsProduct) {
//            $orderProduct = (new OrderProduct())
//                    ->setOrder($order)
//                    ->setProduct($basketsProduct->getProduct())
//                    ->setPrice($basketsProduct->getProduct()->getPriceAtCoins())
//                    ->setCount($basketsProduct->getCount());
//
//            $basketService->deleteProductAtBasket($basketsProduct->getId());
//            $orderProductRepository->save($orderProduct);
//        }
//
//        $basketService->deleteBasket();
//
//        if (!$authService->isAuth())
//            $session->destroy();
    }
}