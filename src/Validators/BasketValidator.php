<?php

namespace App\Validators;

use Framework\HTTP\Request;
use Framework\Validator;

class BasketValidator extends Validator
{
    public function __construct()
    {
    }

    public function checkStore(Request $request)
    {
        $error = $request->check([
            ['post', 'id', '/^[1-9][0-9]*$/', 'Product code entered incorrectly.'],
            ['post', 'count', '/^[1-9][0-9]{0,6}$/', 'Product count entered incorrectly.'],
        ]);

        if ($error != '') {
            return json_encode([
                'success' => false,
                'error' => $error
            ]);
        }

        return '';
    }

    public function checkUpdate(Request $request)
    {
        $error = $request->check([
            ['get', 'id', '/^[1-9][0-9]*$/', 'Product code entered incorrectly.'],
            ['put', 'count', '/^[1-9][0-9]{0,6}$/', 'Product count entered incorrectly.'],
        ]);

        if ($error != '') {
            return json_encode([
                'success' => false,
                'error' => $error
            ]);
        }

        return '';
    }

    public function checkDelete(Request $request)
    {
        $error = $request->check([
            ['get', 'id', '/^[1-9][0-9]*$/', 'Product code entered incorrectly.'],
        ]);

        if ($error != '') {
            return json_encode([
                'success' => false,
                'error' => $error
            ]);
        }

        return '';
    }
}
