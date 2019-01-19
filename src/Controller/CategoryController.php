<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Services\CategoryService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class CategoryController extends BaseController
{
    private $categoryRepository;
    private $categoryService;

    public function __construct(CategoryRepository $categoryRepository, CategoryService $categoryService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): string
    {
        $categoryId = $request->get('id');
        $currentCategory = $this->categoryRepository->findById($categoryId);

        if ($currentCategory === null) {
            Response::setResponseCode(404);
            return UserView::render('404');
        }

        $pagesCount = $this->categoryService->calcPagesCount($categoryId);
        $currentPage = 1;

        if ($request->issetGet('page')) {
            $currentPage = $request->get('page');
            if ($currentPage > $pagesCount || $currentPage < 1) {
                return Response::redirect('/category/' . $categoryId);
            }
        }

        return UserView::render('category', array(
                'categoryCurrent' => $currentCategory,
                'products' => $this->categoryService->getProductsByPage($categoryId, $currentPage),
                'pagesCount' => $pagesCount,
                'currentPage' => $currentPage
        ));
    }
}