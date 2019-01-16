<?php

namespace App\Controller;

use App\Models\ContactPerson;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Repository\ContactPersonRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Services\AuthService;
use App\Services\BasketService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\Session;

class OrderController extends BaseController
{
    public function __construct()
    {
    }

    public function index()
    {
        // todo check empty basket
        return UserView::render('order');
    }

    // todo: move to service
    public function store(Request $request, ContactPersonRepository $contactPersonRepository, AuthService $authService,
                          OrderRepository $orderRepository, BasketService $basketService,
                          OrderProductRepository $orderProductRepository, Session $session)
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
        $contactPerson = $contactPersonRepository->save($contactPerson);

        if ($authService->isAuth())
            $user = (new User())->setId($authService->getUserId());
        else
            $user = (new User())->setId(0);

        $order = (new Order())
                ->setUser($user)
                ->setContactPerson($contactPerson)
                ->setConfirm(0)
                ->setComment($request->post('comment'));

        $order = $orderRepository->save($order);

        $basketsProducts = $basketService->getBasketProducts();

        foreach ($basketsProducts as $basketsProduct) {
            $orderProduct = (new OrderProduct())
                    ->setOrder($order)
                    ->setProduct($basketsProduct->getProduct())
                    ->setPrice($basketsProduct->getProduct()->getPriceAtCoins())
                    ->setCount($basketsProduct->getCount());

            $basketService->deleteProductAtBasket($basketsProduct->getId());
            $orderProductRepository->save($orderProduct);
        }

        $basketService->deleteBasket();

        if (!$authService->isAuth())
            $session->destroy();

        return UserView::render('order_created', ['orderId' => $order->getId()]);
    }
}