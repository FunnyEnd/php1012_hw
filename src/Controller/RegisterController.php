<?php

namespace App\Controller;

use App\Extensions\UserAlreadyExistExtension;
use App\Services\AuthService;
use App\Services\UserService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class RegisterController extends BaseController
{
    private $userService;
    private $authService;

    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function index()
    {
        return UserView::render('register', [
                'error' => '',
                'email' => '',
                'password' => '',
                'firstName' => '',
                'lastName' => '',
                'phone' => '',
                'city' => '',
                'stock' => ''
        ]);
    }

    public function register(Request $request)
    {
        try {
            $this->userService->create($request);
        } catch (UserAlreadyExistExtension $e) {
            return UserView::render('register', [
                    'error' => 'User already exist',
                    'email' => $request->post('email'),
                    'password' => $request->post('password'),
                    'firstName' => $request->post('first-name'),
                    'lastName' => $request->post('last-name'),
                    'phone' => $request->post('phone'),
                    'city' => $request->post('city'),
                    'stock' => $request->post('stock')
            ]);
        }

        $this->authService->auth(
                $request->post('email'),
                $request->post('password')
        );

        return Response::redirect('/');
    }
}
