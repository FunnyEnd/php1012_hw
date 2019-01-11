<?php

namespace App\Extensions;

use Exception;

class BasketNotExistExtension extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
