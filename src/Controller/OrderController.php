<?php

namespace App\Controller;

use App\Models\ContactPerson;
use App\Models\Order;
use App\Models\User;
use App\Repository\BasketProductRepository;
use App\Repository\ContactPersonRepository;
use App\Repository\OrderRepository;
use App\Services\AuthService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;

class OrderController extends BaseController
{
    public function __construct()
    {
    }

    public function index(Request $request, ContactPersonRepository $contactPersonRepository, AuthService $authService,
                          OrderRepository $orderRepository, BasketProductRepository $basketProductRepository)
    {
        $contactPerson = (new ContactPerson())
                ->setFirstName('alex')
                ->setLastName('kornienko')
                ->setEmail('mail')
                ->setCity('city')
                ->setStock('stock')
                ->setPhone('0995402340');

        $contactPerson = $contactPersonRepository->save($contactPerson);

        if ($authService->isAuth())
            $user = (new User())->setId($authService->getUserId());
        else
            $user = (new User())->setId(0);

        $order = (new Order())
                ->setUser($user)
                ->setContactPerson($contactPerson)
                ->setConfirm(0)
                ->setComment("comment");

        $newOrder = $orderRepository->save($order);

        if ($authService->isAuth())
            $basketsProducts = $basketProductRepository->findByUserId($authService->getUserId());
        else
            $basketsProducts = $basketProductRepository->findByUserId($authService->getUserId()); // todo



        var_dump($newOrder);

        return UserView::render('order');
    }

    public function store()
    {


//        $contactPerson = (new ContactPerson())
//                ->setFirstName('Alex')
//                ->setLastName('Kornienko')
//                ->setEmail('email')
//                ->setCity('city')
//                ->setStock('stock')
//                ->setPhone('0995402340');
    }
}