<?php

namespace App\Services\Category;

use App\Repository\ProductCharacteristicsRepository;
use App\Repository\ProductRepository;
use App\Services\FilterService;
use Framework\Config;
use Framework\HTTP\Request;

class CategoryFilterService extends AbstractCategoryService
{
    private $productsCount;

    private $prodCharRep;
    private $productRepository;
    private $filterService;

    public function __construct(ProductCharacteristicsRepository $prodCharRep, ProductRepository $productRepository,
                                FilterService $filterService)
    {
        $this->prodCharRep = $prodCharRep;
        $this->productRepository = $productRepository;
        $this->filterService = $filterService;
    }

    public function getProducts(Request $request, $currentPage): array
    {
        $filter = $this->filterService->getSelectedValues($request);
        $products = $this->productRepository->findByCategoryId($request->get('id'));

        $result = [];
        foreach ($products as $product) {
            $prodCharArr = $this->getProductCharacteristicAsArray($product->getId());

            if ($this->characteristicEqFilter($filter, $prodCharArr)) {
                array_push($result, $product);
            }
        }

        $this->productsCount = count($result);
        $countAtPage = Config::get('count_at_page');
        $from = ($currentPage - 1) * $countAtPage;

        return array_slice($result, $from, $countAtPage);
    }

    public function getPagesCount(Request $request): int
    {
        if (!isset($this->productsCount)) {
            $this->getProducts($request, 1);
        }

        $countAtPage = Config::get('count_at_page');

        if ($this->productsCount < $countAtPage) {
            return 1;
        }

        return ceil($this->productsCount / $countAtPage);
    }

    private function getProductCharacteristicAsArray(int $id): array
    {
        $prodChars = $this->prodCharRep->findByProductId($id);
        $prodCharArr = [];

        foreach ($prodChars as $char) {
            $prodCharArr[$char->getCharacteristic()->getId()] = $char->getValue();
        }

        return $prodCharArr;
    }

    private function characteristicEqFilter(array $filter, array $prodCharArr): bool
    {
        $res = true;
        foreach ($filter as $id => $value) {
            if (!array_key_exists($id, $prodCharArr) || !in_array($prodCharArr[$id], $value)) {
                $res = false;
                break;
            }
        }

        return $res;
    }
}
