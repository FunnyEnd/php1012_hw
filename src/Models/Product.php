<?php

namespace App\Models;

use App\Extensions\ProductExtension;

class Product
{
    private $products;
    private $acceptOrderBy;
    private $productStock;

    public function __construct()
    {
        $products = include('data/list_of_products.php');
        $this->products = $products;
        $this->acceptOrderBy = array('name', 'price');
        $this->productStock = new ProductStock();
    }

    private function sortByname($a, $b)
    {
        $al = strtolower($a['name']);
        $bl = strtolower($b['name']);
        if ($al == $bl)
            return 0;

        return ($al > $bl) ? +1 : -1;
    }

    private function sortByprice($a, $b)
    {
        $al = floatval($a['price']);
        $bl = floatval($b['price']);
        if ($al == $bl)
            return 0;

        return ($al > $bl) ? +1 : -1;
    }

    public function getById(int $id): array
    {
        $k = array_search($id, array_column($this->products, 'id'));

        if ($k === false) {
            return array();
        }

        $product = $this->products[$k];
        $product['count'] = $this->productStock->getProductCountAtStockById($id);
        return $product;
    }

    public function getByCatId(int $catId, string $orderBy = 'name'): array
    {
        $res = array();
        foreach ($this->products as $p) {
            if ($p['cat'] == $catId) {
                $p['count'] = $this->productStock->getProductCountAtStockById($p['id']);
                array_push($res, $p);
            }
        }
        if (in_array($orderBy, $this->acceptOrderBy))
            usort($res, array("App\Models\Product", "sortBy$orderBy"));

        return $res;
    }
}