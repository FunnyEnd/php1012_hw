<?php

namespace Framework;

use Zaine\Log;

class View
{
    protected const PATH = "src/View/";

    public static function render(string $template, array $data = array(), $templatePath = self::PATH): string
    {
        $path = $templatePath . $template . ".php";

        if (!file_exists($path)) {
            $logger = new Log('View');
            $logger->error("Templates file {$path} dont exist.");
            return '';
        }

        ob_start();
        extract($data, EXTR_OVERWRITE);
        require $path;
        return ob_get_clean();
    }
}