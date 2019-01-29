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
        $error = $request->check([
            ['post', 'email', ':email', 'Email entered incorrectly.'],
            ['post', 'password', ':password', 'Password entered incorrectly.'],
            ['post', 'first-name', '/^[a-z]{2,15}$/i', 'First name entered incorrectly.'],
            ['post', 'last-name', '/^[a-z]{2,15}$/i', 'Last name entered incorrectly.'],
            ['post', 'phone', '/^[0-9]{10}$/', 'Phone entered incorrectly.'],
            ['post', 'city', '/^[a-z]{5,30}$/i', 'City name entered incorrectly.'],
            ['post', 'stock', '/^[a-z0-9 ]{5,30}$/i', 'Stock name entered incorrectly.'],
        ]);

        try {
            $this->userService->create($request);
        } catch (UserAlreadyExistExtension $e) {
            if ($error == '') {
                $error = 'User already registered.';
            }
        }

        if ($error != '') {
            return UserView::render('register', [
                'error' => $error,
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
