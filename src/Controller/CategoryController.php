<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Services\Category\CategoryServiceFactory;
use App\Services\FilterService;
use App\View\UserView;
use Framework\Controller;
use Framework\HTTP\Request;

class CategoryController extends Controller
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
        $currentCategory = $this->categoryRepository->findById($request->fetch('get', 'id'));

        $categoryService = (new CategoryServiceFactory())->getCategoryService($request);

        $currentPage = $categoryService->getCurrentPage($request);
        $products = $categoryService->getProducts($request, $currentPage);
        $pagesCount = $categoryService->getPagesCount($request);
        $characteristics = $this->filterService->getCharacteristics($request);
        $pagination = $categoryService->getPagination($request);

        return UserView::render('category', [
                'categoryCurrent' => $currentCategory,
                'products' => $products,
                'pagesCount' => $pagesCount,
                'currentPage' => $currentPage,
                'characteristics' => $characteristics,
                'pagination' => $pagination
        ]);
    }
}