<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class ProductController extends BaseController
{
    private $productRepository;


    function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function showProduct(Request $request): string
    {
        $product = $this->productRepository->findById($request->get('id'));

        if ($product->isEmpty()) {
            Response::setResponseCode(404);
            return UserView::render('404');
        }

        return UserView::render('product_detailed', array(
                'product' => $product
        ));
    }
}
