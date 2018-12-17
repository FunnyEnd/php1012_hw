<?php

namespace App\TemplateEngine;

class View
{
  private const PATH = "src/TemplateEngine/Teamplates/";

  public static function render(string $string, array $data)
  {
    ob_start();
    extract($data, EXTR_OVERWRITE);
    require self::PATH . $string . ".php";
    return ob_get_clean();
  }
}