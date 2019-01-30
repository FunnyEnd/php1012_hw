<?php

namespace App\Controller;

use App\Services\SearchService;
use App\View\UserView;
use Framework\Controller;
use Framework\HTTP\Request;
use Framework\HTTP\Response;

class SearchController extends Controller
{
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index(Request $request)
    {
        $searchString = $this->searchService->getSearchString($request);
        $pagesCount = $this->searchService->getPagesCount($searchString);
        $currentPage = $this->searchService->getCurrentPage($request);
        $products = $this->searchService->getProducts($searchString, $currentPage);

        $error = null;

        if (count($products) == 0) {
            $error = 'Products dont found.';
        }

        return UserView::render('search', [
            'error' => $error,
            'products' => $products,
            'searchString' => $searchString,
            'searchRequest' => $request->fetch('get', 'search-string'),
            'pagesCount' => $pagesCount,
            'currentPage' => $currentPage
        ]);
    }
}