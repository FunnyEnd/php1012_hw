<?php

namespace Framework;

use UnexpectedValueException;

abstract class Controller
{
    public function hasMethod(string $method): bool
    {
        return method_exists($this, $method);
    }

    public function callMethod(string $method, array $param = array()): string
    {
        if (!$this->hasMethod($method)) {
            $className = get_class($this);
            throw new UnexpectedValueException("Oh, method {$method} don`t exit at {$className}.");
        }
        return call_user_func_array(array($this, $method), $param);
    }
}
