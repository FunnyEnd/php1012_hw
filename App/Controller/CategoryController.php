<?php

namespace App\Controller;

use App\Extensions\CategoryExtension;
use App\Extensions\ProductExtension;
use App\Models\Product;
use App\Models\Category;

class CategoryController
{
  private $product;
  private $category;

  public function __construct()
  {
    $this->product = new Product();
    $this->category = new Category();
  }

  public function showCategory(array $getRequest): string
  {
    try {
      $id = $getRequest['id'];
      $cur_cat = $this->category->getById($id);
      $cur_cat_products = $this->product->getByCatId($id);
      $category = $this->category->getAll();
      return include \App\Config\TEMPLATE_FOLDER . "product_list_at_category.php";
    } catch (ProductExtension $pe) {
      $pe->log();
      http_response_code(404);
    } catch (CategoryExtension $ce) {
      $ce->log();
      http_response_code(404);
    }
  }
}