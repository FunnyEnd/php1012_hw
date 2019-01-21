<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CharacteristicRepository;
use App\Repository\ProductCharacteristicsRepository;
use App\Services\CategoryService;
use App\View\UserView;
use Framework\BaseController;
use Framework\Dispatcher;
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

        if ($currentCategory->isEmpty()) {
            Response::setResponseCode(404);
            return UserView::render('404');
        }

        $pagesCount = $this->categoryService->calcPagesCount($categoryId);
        $currentPage = $this->categoryService->getCurrentPage($request, $pagesCount);

        if ($currentPage === null) {
            return Response::redirect('/category/' . $categoryId);
        }

        $chaRep = Dispatcher::get(CharacteristicRepository::class);
        $chars = $chaRep->findByCategoryId($categoryId);

        $prodCharRep = Dispatcher::get(ProductCharacteristicsRepository::class);

        $filter = '';
        if ($request->issetGet('filter')) {
            $filter = $request->get('filter');
        }

        $char = explode(';', $filter);
        $filterParams = [];
        foreach ($char as $c) {
            $data = explode('=', $c);
            $params = explode(',', $data[1]);
            $filterParams[$data[0]] = $params;
        }

        var_dump($filterParams);

        $characteristics = [];
        foreach ($chars as $key => $cha) {
            $characteristics[$key]['info'] = $cha;
            $arr = $prodCharRep->findValuesByCategoryIdAndCharId($categoryId, $cha->getId());
            $characteristics[$key]['values'] = $arr;
        }

        return UserView::render('category', array(
                'categoryCurrent' => $currentCategory,
                'products' => $this->categoryService->getProductsByPage($categoryId, $currentPage),
                'pagesCount' => $pagesCount,
                'currentPage' => $currentPage,
                'characteristics' => $characteristics
        ));
    }
}