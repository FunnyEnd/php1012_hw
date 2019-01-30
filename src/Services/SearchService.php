<?php
/**
 * Created by PhpStorm.
 * User: FoFF
 * Date: 20.01.2019
 * Time: 0:49
 */

namespace App\Services;

use App\Repository\ProductRepository;
use Framework\Config;
use Framework\HTTP\Request;

class SearchService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts(string $searchString, int $page): array
    {
        $countProductsAtPage = Config::get('count_at_search_page');
        $from = $countProductsAtPage * ($page - 1);
        $products = $this->productRepository->findBySearchStringWithLimit($searchString, $from, $countProductsAtPage);

        return $products;
    }

    public function getSearchString(Request $request): string
    {
        $searchString = $request->fetch('get', 'search-string');
        $searchString = str_replace('+', ' ', $searchString);
        $searchString = urldecode($searchString);

        return $searchString;
    }

    public function getPagesCount(string $searchString): int
    {
        $countProducts = $this->productRepository->findCountBySearchString($searchString);
        $atPage = intval(Config::get('count_at_search_page'));
        $pagesCount = 1;

        if ($countProducts > $atPage) {
            $pagesCount = ceil($countProducts / $atPage);
        }

        return $pagesCount;
    }

    public function getCurrentPage(Request $request): int
    {
        $currentPage = 1;

        if ($request->exist('get', 'page')) {
            return intval($request->fetch('get', 'page'));
        }

        return $currentPage;
    }
}