<?php

namespace App\Controller;

use App\Services\AuthService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class AuthController extends BaseController
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
        $auth = $this->authService->auth(
                $request->post('email'),
                $request->post('password')
        );

        if (!$auth) {
            return UserView::render('auth', [
                    'email' => $request->post('email')
            ]);
        }

        Response::redirect('/');
        return '';
    }

    public function logout()
    {
        $this->authService->logOut();
        Response::redirect('/');
        return '';
    }
}