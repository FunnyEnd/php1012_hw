<?php

namespace App\Controller;

use App\Extensions\UserAlreadyExistExtension;
use App\Services\AuthService;
use App\Services\UserService;
use App\View\UserView;
use Framework\Controller;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class RegisterController extends Controller
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
                'error' => 'User already registered.',
                'email' => $request->fetch('post', 'email'),
                'firstName' => $request->fetch('post', 'first-name'),
                'lastName' => $request->fetch('post', 'last-name'),
                'phone' => $request->fetch('post', 'phone'),
                'city' => $request->fetch('post', 'city'),
                'stock' => $request->fetch('post', 'stock')
            ]);
        }

        $this->authService->auth(
            $request->fetch('post', 'email'),
            $request->fetch('post', 'password')
        );

        return Response::redirect('/');
    }
}
