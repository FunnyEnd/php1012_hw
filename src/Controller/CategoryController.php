<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Services\Category\CategoryServiceFactory;
use App\Services\FilterService;
use App\View\UserView;
use Framework\BaseController;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class CategoryController extends BaseController
{
    private $categoryRepository;
    private $filterService;

    public function __construct(CategoryRepository $categoryRepository, FilterService $filterService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->filterService = $filterService;
    }

    public function index(Request $request): string
    {
        $currentCategory = $this->categoryRepository->findById($request->get('id'));

        if ($currentCategory->isEmpty()) {
            Response::setResponseCode(404);
            return UserView::render('404');
        }

        $categoryService = (new CategoryServiceFactory())->getCategoryService($request);
        $currentPage = $categoryService->getCurrentPage($request);

        return UserView::render('category', array(
                'categoryCurrent' => $currentCategory,
                'products' => $categoryService->getProducts($request, $currentPage),
                'pagesCount' => $categoryService->getPagesCount($request),
                'currentPage' => $currentPage,
                'characteristics' => $this->filterService->getCharacteristics($request),
                'pagination' => $categoryService->getPagination($request)
        ));
    }
}