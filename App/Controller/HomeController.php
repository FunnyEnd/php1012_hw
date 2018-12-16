<?php

namespace App\Controller;

use App\Extensions\CategoryExtension;
use App\Extensions\ProductExtension;
use App\Models\Product;
use App\Models\Category;

class HomeController
{
  private $product;
  private $category;

  public function __construct()
  {
    $this->product = new Product();
    $this->category = new Category();
  }

  public function showHome(): string
  {
    try {
      $category = $this->category->getAll();
      $cat_1_products = $this->product->getByCatId(1);
      $cat_2_products = $this->product->getByCatId(2);
      $cat_3_products = $this->product->getByCatId(3);
      return include \App\Config\TEMPLATE_FOLDER . "home.php";
    } catch (ProductExtension $pe) {
      $pe->log();
      http_response_code(404);
    } catch (CategoryExtension $ce) {
      $ce->log();
      http_response_code(404);
    }
  }
}