<?php

namespace Framework;

use Framework\Exception\ViewExtension;

class View
{
    protected const PATH = "src/View/";

    public static function render(string $template, array $data = array(), $templatePath = self::PATH): string
    {
        try {
            $path = $templatePath . $template . ".php";
            if (!file_exists($path))
                throw new ViewExtension("Templates file {$path} dont exist.");

            ob_start();
            extract($data, EXTR_OVERWRITE);
            require $path;
            return ob_get_clean();
        } catch (ViewExtension $ve) {
            $ve->log();
        }

        return '';
    }
}