<?php

namespace App\Models;

class Product
{
    private $products;
    private $acceptOrderBy;

    public function __construct()
    {
        $products = include('list_of_products.php');
        $this->products = $products;
        $this->acceptOrderBy = array('name', 'price');
    }

    private function sortByname($a, $b)
    {
        $al = strtolower($a['name']);
        $bl = strtolower($b['name']);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }

    private function sortByprice($a, $b)
    {
        $al = floatval($a['price']);
        $bl = floatval($b['price']);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }

    public function getById($id)
    {
        $k = array_search(intval($id), array_column($this->products, 'id'));

        if ($k === false)
            die("Product with index $id don`t exist!");

        return $this->products[$k];
    }

    public function getByCatId($catId, $orderBy = 'name')
    {
        $res = array();

        foreach ($this->products as $p) {
            if ($p['cat'] == $catId)
                array_push($res, $p);
        }


        if (in_array($orderBy, $this->acceptOrderBy))
            usort($res, array("App\Models\Product", "sortBy$orderBy"));

        return $res;
    }
}