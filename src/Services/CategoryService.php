<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Framework\Config;
use Framework\HTTP\Request;

class CategoryService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function calcPagesCount(int $categoryId): int
    {
        $count = $this->productRepository->findCountByCategoryId($categoryId);
        $countAtPage = Config::get('count_at_page');

        if ($count < $countAtPage) {
            return 1;
        }

        return ceil($count / $countAtPage);
    }

    public function getProductsByPage(int $categoryId, int $currentPage, int $countAtPage = null): array
    {
        if ($countAtPage === null) {
            $countAtPage = Config::get('count_at_page');
        }

        $from = ($currentPage - 1) * $countAtPage;
        return $this->productRepository->findByCategoryIdWithLimit($categoryId, $from, $countAtPage);
    }

    public function getCurrentPage(Request $request, int $pagesCount): int
    {
        $currentPage = 1;

        if ($request->issetGet('page')) {
            $currentPage = intval($request->get('page'));
            if ($currentPage > $pagesCount || $currentPage < 1) {
                return null;
            }
        }

        return $currentPage;
    }
}
