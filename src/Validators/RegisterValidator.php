<?php

namespace App\Validators;

use App\View\UserView;
use Framework\HTTP\Request;
use Framework\Validator;

class RegisterValidator extends Validator
{
    public function __construct()
    {
    }

    public function check(Request $request)
    {
        $error = $request->check([
            ['post', 'email', ':email', 'Email entered incorrectly.'],
            ['post', 'password', ':password', 'Password entered incorrectly.'],
            ['post', 'first-name', '/^[a-z]{2,15}$/i', 'First name entered incorrectly.'],
            ['post', 'last-name', '/^[a-z]{2,15}$/i', 'Last name entered incorrectly.'],
            ['post', 'phone', '/^[0-9]{10}$/', 'Phone entered incorrectly.'],
            ['post', 'city', '/^[a-z\s]{5,30}$/i', 'City name entered incorrectly.'],
            ['post', 'stock', '/^[a-z0-9\s]{5,30}$/i', 'Stock name entered incorrectly.'],
        ]);

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

        return '';
    }
}
