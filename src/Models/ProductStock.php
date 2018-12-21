<?php

namespace App\Models;

class ProductStock
{
  private $productAtStock;

  public function __construct()
  {
    $productAtStock = require('data/list_of_product_at_stock.php');
    $this->productAtStock = $productAtStock;
  }

  public function getProductCountAtStockById(int $id): int
  {
    $k = array_search($id, array_column($this->productAtStock, 'product_id'));
    return $this->productAtStock[$k]['in_stock'];
  }
}