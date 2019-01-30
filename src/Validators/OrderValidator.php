<?php

namespace App\Validators;

use App\Services\AuthService;
use App\Services\Basket\BasketServiceFactory;
use App\View\UserView;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Validator;

class OrderValidator extends Validator
{
    private $basketService;

    public function __construct(BasketServiceFactory $basketServiceFactory, AuthService $authService)
    {
        $this->basketService = $basketServiceFactory->getBasketService($authService->isAuth());
    }

    public function check()
    {
        if ($this->basketService->isEmpty()) {
            return Response::redirect('/');
        }

        return '';
    }

    public function checkStore(Request $request)
    {
        if ($this->basketService->isEmpty()) {
            return Response::redirect('/');
        }

        $error = $request->check([
            ['post', 'first-name', '/^[a-zA-Zа-яА-Я]{2,25}$/', 'First name entered incorrectly.'],
            ['post', 'last-name', '/^[a-zA-Zа-яА-Я]{2,25}$/', 'Last name entered incorrectly.'],
            ['post', 'email', ':email', 'Email entered incorrectly.'],
            ['post', 'city', '/^[a-z\s]{5,30}$/i', 'City entered incorrectly.'],
            ['post', 'stock', '/^[a-z0-9\s]{5,30}$/i', 'Stock entered incorrectly.'],
            ['post', 'phone', '/^[0-9]{10}$/', 'Phone entered incorrectly.'],
            ['post', 'comment', '/^[a-z0-9\s]*$/i', 'Comment entered incorrectly.'],
        ]);

        if ($error != '') {
            return UserView::render('order', [
                'error' => $error,
                'email' => $request->fetch('post', 'email'),
                'firstName' => $request->fetch('post', 'first-name'),
                'lastName' => $request->fetch('post', 'last-name'),
                'phone' => $request->fetch('post', 'phone'),
                'city' => $request->fetch('post', 'city'),
                'stock' => $request->fetch('post', 'stock'),
                'comment' => $request->fetch('post', 'comment')
            ]);
        }

        return '';
    }
}
