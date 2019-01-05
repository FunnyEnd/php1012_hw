<?php

namespace Framework;

use Framework\Exception\ViewExtension;

class View
{
    private const PATH = "src/View/";

    public static function render(string $template, array $data = array()): string
    {
        try {
            $path = self::PATH . $template . ".php";
            if (!file_exists($path))
                throw new ViewExtension("Templates file {$path} dont exist.");

            ob_start();
            extract($data, EXTR_OVERWRITE);
            require self::PATH . $template . ".php";
            return ob_get_clean();
        } catch (ViewExtension $ve) {
            $ve->log();
        }

        return '';
    }
}