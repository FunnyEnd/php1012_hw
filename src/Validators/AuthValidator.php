<?php

namespace App\Validators;

use App\View\UserView;
use Framework\HTTP\Request;
use Framework\Validator;

class AuthValidator extends Validator
{
    public function __construct()
    {
    }

    public function checkAuth(Request $request)
    {
        $error = $request->check([
            ['post', 'email', ':email', 'Email entered incorrectly.'],
            ['post', 'password', ':password', 'Password entered incorrectly.'],
        ]);

        if ($error != '') {
            return UserView::render('auth', [
                'error' => $error,
                'email' => $request->fetch('post', 'email')
            ]);
        }

        return '';
    }
}
