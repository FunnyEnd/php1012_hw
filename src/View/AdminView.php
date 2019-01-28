<?php

namespace App\View;

use Framework\View;

class AdminView extends View
{
    protected const PATH = "../src/View/admin/";

    public static function render(string $template, array $data = array(), $templatePath = self::PATH): string
    {
        return parent::render($template, $data, $templatePath);
    }
}
