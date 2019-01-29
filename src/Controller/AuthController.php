<?php

namespace App\Controller;

use App\Services\AuthService;
use App\View\UserView;
use Framework\Controller;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        return UserView::render('auth', [
            'email' => '',
            'password' => ''
        ]);
    }

    public function auth(Request $request)
    {
        $error = $request->check([
            ['post', 'email', ':email', 'Email entered incorrectly.'],
            ['post', 'password', ':password', 'Password entered incorrectly.'],
        ]);

        if($error != ''){
            return UserView::render('auth', [
                'error' => $error,
                'email' => $request->fetch('post','email')
            ]);
        }

        $auth = $this->authService->auth(
            $request->fetch('post','email'),
            $request->fetch('post','password')
        );

        if (!$auth) {
            return UserView::render('auth', [
                'email' => $request->fetch('post','email')
            ]);
        }

        return Response::redirect('/');
    }

    public function logout()
    {
        $this->authService->logOut();
        return Response::redirect('/');
    }
}