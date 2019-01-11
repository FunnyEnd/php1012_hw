<?php

namespace App\Controller;

use App\Extensions\ProductNotExistExtension;
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

    /**
     * @todo redirect to page product don`t exist
     * @param Request $request
     * @return string
     */
    public function showProduct(Request $request): string
    {
        $productId = $request->get('id');
        try {
            $product = $this->productRepository->findById($productId);
        } catch (ProductNotExistExtension $e) {
            Response::setResponseCode(404);
            return UserView::render('404');
        }

        return UserView::render('product_detailed', array(
                'product' => $product
        ));
    }
}
