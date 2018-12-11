<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 10.12.18
 * Time: 17:55
 */

class ProductRepository
{
    private $products;
    private $acceptOrderBy;

    private function sortByname($a, $b){
        $al = strtolower($a['name']);
        $bl = strtolower($b['name']);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }

    private function sortByprice($a, $b){
        $al = floatval($a['price']);
        $bl = floatval($b['price']);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }

    public function __construct($products)
    {
        $this->products = $products;
        $this->acceptOrderBy = array('name', 'price');
    }

    public function getById($id){
        $k = array_search(intval($id), array_column($this->products, 'id'));

        if($k === false)
            die("Product with index $id don`t exist!");

        return $this->products[$k];
    }

    public function getByCatId($catId, $orderBy = 'name'){
        $res = array();

        foreach ($this->products as $p){
            if($p['cat'] == $catId)
                array_push($res, $p);
        }


        if(in_array($orderBy, $this->acceptOrderBy))
            usort($res, array("ProductRepository", "sortBy$orderBy"));

        return $res;
    }


}