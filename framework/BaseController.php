<?php

namespace Framework;

use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

abstract class BaseController
{
    public function hasMethod(string $method): bool
    {
        return method_exists($this, $method);
    }

    /**
     * This method checks if the method being tested contains a Request type argument.
     * @param string $method
     * @return bool
     */
    public function methodHasRequestParam(string $method): bool
    {
        try {
            if (!$this->hasMethod($method)) {
                $className = get_class($this);
                throw new UnexpectedValueException(
                        "Oh, method {$method} don`t exit at {$className}.");
            }
            $reflector = new ReflectionClass(get_class($this));
            if (count($reflector->getMethod($method)->getParameters()) != 0)
                return true;

        } catch (ReflectionException $e) {
            die($e->getMessage());
        }

        return false;
    }

    public function callMethod(string $method, array $param = array()): string
    {
        if (!$this->hasMethod($method)) {
            $className = get_class($this);
            throw new UnexpectedValueException(
                    "Oh, method {$method} don`t exit at {$className}.");
        }
        return call_user_func_array(array($this, $method), $param);
    }
}