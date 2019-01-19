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

        if (!$this->categoryService->validPage($request, $pagesCount)) {
            return Response::redirect('/category/' . $categoryId);
        }

        $currentPage = ($request->issetGet('page')) ? $request->get('page') : 1;

        return UserView::render('category', array(
                'categoryCurrent' => $currentCategory,
                'products' => $this->categoryService->getProductsByPage($categoryId, $currentPage),
                'pagesCount' => $pagesCount,
                'currentPage' => $currentPage
        ));
    }
}