<?php

namespace App\Controller;

use App\Extensions\ProductNotExistExtension;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\View;

class ProductController extends BaseController
{
    private $productRepository;
    private $categoryRepository;

    function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @todo redirect to page product don`t exist
     * @param Request $request
     * @return string
     */
    public function showProduct(Request $request): string
    {
        $productId = $request->get("id");
        try {
            $product = $this->productRepository->findById($productId);
        } catch (ProductNotExistExtension $e) {
            Response::setResponseCode(404);
            return View::render('404');
        }

        $category = $this->categoryRepository->findAll();
        return View::render('product_detailed', array(
                'category' => $category,
                'product' => $product
        ));
    }
}