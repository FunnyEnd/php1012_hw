<?php

namespace App\Services\Category;

use App\Repository\ProductRepository;
use Framework\Config;
use Framework\HTTP\Request;

class CategoryDefaultService extends AbstractCategoryService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts(Request $request, $currentPage): array
    {
        $countAtPage = Config::get('count_at_page');
        $from = ($currentPage - 1) * $countAtPage;
        return $this->productRepository->findByCategoryIdWithLimit($request->get('id'), $from, $countAtPage);
    }

    public function getPagesCount(Request $request): int
    {
        $count = $this->productRepository->findCountByCategoryId($request->get('id'));
        $countAtPage = Config::get('count_at_page');

        if ($count < $countAtPage) {
            return 1;
        }

        return ceil($count / $countAtPage);
    }
}