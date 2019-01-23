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
        $currentPage = $this->searchService->getCurrentPage($request, $pagesCount);

        if ($currentPage === null) {
            Response::redirect('/');
        }

        $products = $this->searchService->getProducts($searchString, $currentPage);

        return UserView::render('search', [
                'products' => $products,
                'searchString' => $searchString,
                'searchRequest' => $request->get('search-string'),
                'pagesCount' => $pagesCount,
                'currentPage' => $currentPage
        ]);
    }
}