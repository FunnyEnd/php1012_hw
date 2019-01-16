<?php

namespace App\Controller;

use App\View\UserView;
use Framework\BaseController;

class OrderController extends BaseController
{
    public function __construct()
    {
    }

    public function index()
    {
        return UserView::render('order');
    }
}