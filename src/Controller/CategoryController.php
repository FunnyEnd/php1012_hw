<?php

namespace App\Controller;

use App\Extensions\CategoryNotExistExtension;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\AuthService;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\View;

class CategoryController extends BaseController
{
    private $productRepository;
    private $categoryRepository;
    private $authService;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository, AuthService $authService)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authService = $authService;
    }

    /**
     * @todo page category don`t exist
     * @param Request $request
     * @return string
     */
    public function showCategory(Request $request): string
    {
        $id = $request->get('id');
        try {
            $currentCategory = $this->categoryRepository->findById($id);
        } catch (CategoryNotExistExtension $e) {
            Response::setResponseCode(404);
            return View::render('404');
        }

        $products = $this->productRepository->findByCategoryId($id);
        $categoryList = $this->categoryRepository->findAll();
        $isAuth = $this->authService->isAuth();

        return View::render('product_list_at_category', array(
                'categoryCurrent' => $currentCategory,
                'auth' => $isAuth,
                'products' => $products,
                'category' => $categoryList
        ));
    }
}