<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Services\AuthService;
use Framework\HTTP\Response;

class HomeController extends BaseController
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(): string
    {
        if (!$this->guard('admin')) {
            return Response::redirect('/auth');
        }

        return Response::redirect('admin/order');
    }


}