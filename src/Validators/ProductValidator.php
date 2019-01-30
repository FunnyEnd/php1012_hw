<?php

namespace App\Validators;

use App\Repository\ProductRepository;
use App\View\UserView;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Validator;

class ProductValidator extends Validator
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function check(Request $request)
    {
        $error = $request->check([
            ['get', 'id', '/^[0-9]+$/', 'Id entered incorrectly.'],
        ]);

        if ($error == '') {
            $count = $this->productRepository->findCount('products.id = :id', [
                'id' => $request->fetch('get', 'id')
            ]);

            if ($count != 1) {
                Response::setResponseCode(404);
                $error = 'Product don`t exist.';
            }
        }

        if ($error != '') {
            return UserView::render('product_dont_exist', [
                'error' => $error
            ]);
        }

        return '';
    }
}
