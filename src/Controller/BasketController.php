<?php

namespace App\Controller;

use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;

class BasketController extends BaseController
{

    public function __construct()
    {
    }

    public function index()
    {
        return 'basket';
    }

    public function store(Request $request)
    {
        return '';
    }

}