<?php

namespace Framework\TemplateEngine;

use Framework\Exception\ViewExtension;

class View
{
    private const PATH = "Templates/";

    public static function render(string $string, array $data = array()): string
    {
        try {
            $path = self::PATH . $string . ".php";
            if (!file_exists($path))
                throw new ViewExtension("Templates file {$path} dont exist.");

            ob_start();
            extract($data, EXTR_OVERWRITE);
            require self::PATH . $string . ".php";
            return ob_get_clean();
        } catch (ViewExtension $ve) {
            $ve->log();
        }

        return '';
    }
}